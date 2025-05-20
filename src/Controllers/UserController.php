<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Services\UserService;

class UserController extends BaseController
{
    protected $service;

    public function __construct()
    {
        $this->service = new UserService();
    }

    public function index(Request $request, Response $response): Response
    {
        $data = $this->service->all();
        return $this->json($response, $data);
    }

    public function store(Request $request, Response $response): Response
    {
        $input = $request->getParsedBody();
        if (!is_array($input)) {
            return $this->json($response, ['error' => 'Invalid or missing JSON body'], 400);
        }
        $user = $this->service->create($input);
        return $this->json($response, $user, 201);
    }

    public function show(Request $request, Response $response, $args): Response
    {
        $user = $this->service->get($args['id']);
        if (!$user) {
            return $response->withStatus(404);
        }
        return $this->json($response, $user);
    }

    public function update(Request $request, Response $response, $args): Response
    {
        $input = $request->getParsedBody();
        $user = $this->service->update($args['id'], $input);
        if (!$user) {
            return $response->withStatus(404);
        }
        return $this->json($response, $user);
    }

    public function destroy(Request $request, Response $response, $args): Response
    {
        $deleted = $this->service->delete($args['id']);
        return $response->withStatus($deleted ? 204 : 404);
    }
}
