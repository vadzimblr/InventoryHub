<?php

namespace App\Http\Controllers;

use App\DTOs\Request\NotificationRequestDto;
use App\Enums\RoleType;
use App\Services\NotificationService\Api\NotificationServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class NotificationController extends Controller
{
    public function __construct(
        private readonly NotificationServiceInterface $notificationService)
    {
    }

    public function create(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'receiver_email' => 'nullable|email|exists:users,email',
            'department' => 'required|string|exists:roles,name',
            'type' => 'required|string',
            'content' => 'required|string',
        ]);
        $validated['senderId'] = Auth::id();
        $notificationRequestDto = NotificationRequestDto::fromArray($validated);
        $notificationResponseDto = $this->notificationService->createNotification($notificationRequestDto);

        return response()->json($notificationResponseDto, Response::HTTP_CREATED);
    }

    public function getForDepartment(Request $request, string $department): JsonResponse
    {
        $notifications = $this->notificationService->getNotificationsForDepartment(RoleType::from($department));

        return response()->json($notifications, Response::HTTP_OK);
    }

    public function handle(Request $request, int $notificationId): JsonResponse
    {
        $userId = Auth::user()->id;

        $notification = $this->notificationService->handleNotification($userId, $notificationId);

        return response()->json($notification, Response::HTTP_OK);
    }

    public function getUnfinishedForUser(Request $request): JsonResponse
    {
        $userId = Auth::user()->id;

        $notifications = $this->notificationService->getUnfinishedNotificationsForUser($userId);

        return response()->json($notifications, Response::HTTP_OK);
    }

    public function markAsFinished(Request $request, int $notificationId): JsonResponse
    {
        $userId = Auth::user()->id;

        try {
            $notification = $this->notificationService->markAsFinished($notificationId, $userId);
            return response()->json($notification, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_FORBIDDEN);
        }
    }
}
