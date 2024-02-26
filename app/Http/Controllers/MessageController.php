<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\Message\CreateMessageRequest;
use App\Http\Requests\Message\IndexMessageRequest;
use App\Http\Resources\MessageResource;
use App\Services\MessageService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as ResponseCode;

class MessageController extends Controller
{
    public function __construct(private readonly MessageService $messageService)
    {
    }

    public function index(IndexMessageRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $filteredMessages = $this->messageService->getFilteredMessages(
            $validated['orderById'] ?? false,
            $validated['only'] ?? null ,
        );

        return response()->json(MessageResource::collection($filteredMessages) ,
            ResponseCode::HTTP_OK);
    }

    public function show(int $id): JsonResponse
    {
        return ResponseHelper::outOfOrderJson();
    }

    public function store(CreateMessageRequest $request): JsonResponse
    {
        if(!user()->hasPermissionTo('create_message')){

            return ResponseHelper::forbiddenJson();
        }

        $validated = $request->validated();

        $createdMessage = $this->messageService->createMessage($validated);

        return response()->json(new MessageResource($createdMessage), ResponseCode::HTTP_CREATED);
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
