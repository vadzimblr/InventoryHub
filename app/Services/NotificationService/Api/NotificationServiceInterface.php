<?php

namespace App\Services\NotificationService\Api;

use App\DTOs\Request\NotificationRequestDto;
use App\DTOs\Response\NotificationResponseDto;
use App\Enums\RoleType;

interface NotificationServiceInterface
{
    public function createNotification(NotificationRequestDto $notificationRequestDto):NotificationResponseDto;
    public function getNotificationsForDepartment(RoleType $department):array;
    public function handleNotification(int $userId, int $notificationId):NotificationResponseDto;
    public function getUnfinishedNotificationsForUser(int $userId):array;
    public function markAsFinished(int $notificationId, ?int $userId): NotificationResponseDto;
}
