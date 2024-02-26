<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        return ResponseHelper::outOfOrderJson();
    }

    public function show(int $id): JsonResponse
    {
        return ResponseHelper::outOfOrderJson();
    }

    public function store(): JsonResponse
    {
        return ResponseHelper::outOfOrderJson();
    }

    public function update(int $id): JsonResponse
    {
        return ResponseHelper::outOfOrderJson();
    }

    public function destroy(int $id): JsonResponse
    {
        return ResponseHelper::outOfOrderJson();
    }
}
