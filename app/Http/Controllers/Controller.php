<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

abstract class Controller
{
    public function notFoundJson(array $data = ['message' => 'Not found']): JsonResponse
    {
        return $this->errorJson($data, Response::HTTP_NOT_FOUND);
    }

    public function errorJson(array $data, int $statusCode): JsonResponse
    {
        return response()->json($data, $statusCode);
    }
}
