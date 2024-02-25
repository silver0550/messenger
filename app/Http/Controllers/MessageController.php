<?php

namespace App\Http\Controllers;

use App\Contracts\ApiCrudable;
use Illuminate\Http\JsonResponse;

class MessageController extends Controller implements ApiCrudable
{
    public function index(): JsonResponse
    {
        return response()->json('index', 200);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json('show', 200);
    }

    public function store(): JsonResponse
    {
        return response()->json('store', 200);
    }

    public function update(int $id): JsonResponse
    {
        return response()->json('update', 200);
    }

    public function destroy(int $id): JsonResponse
    {
        return response()->json('destroy', 200);
    }
}
