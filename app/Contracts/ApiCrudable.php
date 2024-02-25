<?php

namespace App\Contracts;

use Illuminate\Http\JsonResponse;

interface ApiCrudable
{
    public function index(): JsonResponse;

    public function show(int $id): JsonResponse;

    public function store(): JsonResponse;

    public function update(int $id): JsonResponse;

    public function destroy(int $id): JsonResponse;
}
