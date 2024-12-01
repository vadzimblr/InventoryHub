<?php

namespace App\Services\NotificationService;

use App\DTOs\Request\NotificationRequestDto;
use App\DTOs\Response\NotificationResponseDto;
use App\Enums\RoleType;
use App\Models\Notification;
use App\Models\User;
use App\Services\NotificationService\Api\NotificationServiceInterface;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class NotificationService implements NotificationServiceInterface
{

    public function createNotification(NotificationRequestDto $notificationRequestDto): NotificationResponseDto
    {
        $notification = DB::transaction(function () use ($notificationRequestDto) {
            $notification = $notificationRequestDto->toModel();
            $notification->save();
            return $notification;
        });

        return NotificationResponseDto::fromModel($notification);
    }

    public function getNotificationsForDepartment(RoleType $department): array
    {
        $role = Role::where('name', $department->value)->firstOrFail();
        $notifications = Notification::where('department_id', $role->id)->get();

        return NotificationResponseDto::fromModelCollection($notifications);
    }

    public function handleNotification(int $userId, int $notificationId): NotificationResponseDto
    {
        $notification = Notification::findOrFail($notificationId);
        $user = User::findOrFail($userId);

        $notification->handler_id = $user->id;
        $notification->save();

        return NotificationResponseDto::fromModel($notification);
    }

    public function getUnfinishedNotificationsForUser(int $userId): array
    {
        $notifications = Notification::where('handler_id', $userId)
            ->whereNull('finished_at')
            ->get();

        return NotificationResponseDto::fromModelCollection($notifications);
    }

    public function markAsFinished(int $notificationId, ?int $userId): NotificationResponseDto
    {
        $notification = Notification::findOrFail($notificationId);
        $user = User::findOrFail($userId);

        if ($notification->handler_id && $notification->handler_id !== $user->id && !$user->hasRole('admin')) {
            throw new \Exception('Only the handler or an admin can mark this notification as finished.');
        }
        $notification->finished_at = now();
        $notification->save();
        $notification->refresh();

        return NotificationResponseDto::fromModel($notification);
    }
}
