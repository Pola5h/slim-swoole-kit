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

        // Validate required fields
        if (empty($input['name']) || empty($input['email'])) {
            return $this->json($response, [
                'error' => 'Name and email are required fields'
            ], 400);
        }

        // Validate email format
        if (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
            return $this->json($response, [
                'error' => 'Invalid email format'
            ], 400);
        }

        try {
            $user = $this->service->create($input);
            return $this->json($response, $user, 201);
        } catch (\Exception $e) {
            return $this->json($response, [
                'error' => 'Failed to create user: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(Request $request, Response $response, $args): Response
    {
        $user = $this->service->get($args['id']);
        if (!$user) {
            return $this->json($response, ['error' => 'User not found'], 404);
        }
        return $this->json($response, $user);
    }

    public function update(Request $request, Response $response, $args): Response
    {
        $input = $request->getParsedBody();
        if (!is_array($input)) {
            return $this->json($response, ['error' => 'Invalid or missing JSON body'], 400);
        }

        // Validate email format if provided
        if (isset($input['email']) && !filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
            return $this->json($response, [
                'error' => 'Invalid email format'
            ], 400);
        }

        try {
            $user = $this->service->update($args['id'], $input);
            if (!$user) {
                return $this->json($response, ['error' => 'User not found'], 404);
            }
            return $this->json($response, $user);
        } catch (\Exception $e) {
            return $this->json($response, [
                'error' => 'Failed to update user: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Request $request, Response $response, $args): Response
    {
        try {
            $deleted = $this->service->delete($args['id']);
            if (!$deleted) {
                return $this->json($response, ['error' => 'User not found'], 404);
            }
            return $response->withStatus(204);
        } catch (\Exception $e) {
            return $this->json($response, [
                'error' => 'Failed to delete user: ' . $e->getMessage()
            ], 500);
        }
    }
}
