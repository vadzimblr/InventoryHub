<?php

namespace App\DTOs\Response;

use App\Models\Notification;
use Illuminate\Database\Eloquent\Collection;
use Spatie\LaravelData\Data;

class NotificationResponseDto extends Data
{
    public function __construct(
        public readonly int $id,
        public readonly string $senderEmail,
        public readonly ?string $receiverEmail,
        public readonly ?string $handlerEmail,
        public readonly ?string $departmentName,
        public readonly ?string $type,
        public readonly ?string $content,
        public readonly ?string $createdAt,
        public readonly ?string $finishedAt,
    ) {}

    public static function fromModel(Notification $notification): self
    {
        return new self(
            id: $notification->id,
            senderEmail: $notification->sender?->email ?? 'unknown',
            receiverEmail: $notification->receiver?->email,
            handlerEmail: $notification->handler?->email,
            departmentName: $notification->department?->name,
            type: $notification->type,
            content: $notification->content,
            createdAt: $notification->created_at?->toDateTimeString(),
            finishedAt: $notification->finished_at?->toDateTimeString(),
        );
    }

    public static function fromModelCollection(Collection $notifications): array
    {
        return $notifications->map(fn(Notification $notification) => self::fromModel($notification))->toArray();
    }
}
