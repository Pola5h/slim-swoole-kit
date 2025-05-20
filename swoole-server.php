<?php

use Slim\Factory\AppFactory;
use Swoole\Http\Server;
use Swoole\Http\Request as SwooleRequest;
use Swoole\Http\Response as SwooleResponse;
use Slim\Psr7\Factory\ServerRequestFactory;

require __DIR__ . '/vendor/autoload.php';

$app = require __DIR__ . '/src/bootstrap.php';

$server = new Server("0.0.0.0", 9501);

$server->on("request", function (SwooleRequest $swooleRequest, SwooleResponse $swooleResponse) use ($app) {
    $psrFactory = new ServerRequestFactory();

    $headers = [];
    foreach ($swooleRequest->header ?? [] as $key => $value) {
        $headers[$key] = $value;
    }

    $uri = $swooleRequest->server['request_uri'] ?? '/';
    $query = isset($swooleRequest->server['query_string']) && $swooleRequest->server['query_string']
        ? '?' . $swooleRequest->server['query_string']
        : '';
    $fullUri = $uri . $query;

    $method = $swooleRequest->server['request_method'] ?? 'GET';

    $psrRequest = $psrFactory->createServerRequest($method, $fullUri, $swooleRequest->server)
        ->withQueryParams($swooleRequest->get ?? [])
        ->withParsedBody($swooleRequest->post ?? [])
        ->withCookieParams($swooleRequest->cookie ?? [])
        ->withUploadedFiles([])
        ->withHeader('host', $headers['host'] ?? 'localhost');

    // Manually parse JSON body for Swoole
    if (
        isset($headers['content-type']) &&
        strpos($headers['content-type'], 'application/json') !== false &&
        !empty($swooleRequest->rawContent())
    ) {
        $json = json_decode($swooleRequest->rawContent(), true);
        if (json_last_error() === JSON_ERROR_NONE) {
            $psrRequest = $psrRequest->withParsedBody($json);
        }
    }

    foreach ($headers as $name => $value) {
        $psrRequest = $psrRequest->withHeader($name, $value);
    }

    ob_start();
    $response = $app->handle($psrRequest);
    $response->getBody()->rewind();
    $swooleResponse->status($response->getStatusCode());

    foreach ($response->getHeaders() as $name => $values) {
        foreach ($values as $value) {
            $swooleResponse->header($name, $value);
        }
    }

    $swooleResponse->end((string) $response->getBody());
    ob_end_clean();
});

$server->start();
