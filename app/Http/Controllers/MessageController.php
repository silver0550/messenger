<?php

namespace App\Http\Controllers;

use App\Contracts\ApiCrudable;
use App\Http\Requests\MessageRequest;
use App\Services\MessageService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as ResponseCode;

class MessageController extends Controller /*implements ApiCrudable*/
{
    public function __construct(private readonly MessageService $messageService)
    {
    }

    public function index(): JsonResponse
    {
        return response()->json('index', 200);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json('show', 200);
    }

    public function store(MessageRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $this->messageService->createMessage($validated); //TODO: ITT vagyok

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
