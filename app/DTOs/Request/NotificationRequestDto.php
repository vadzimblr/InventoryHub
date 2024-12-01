<?php

namespace App\DTOs\Request;

use App\Enums\RoleType;
use App\Models\Notification;
use App\Models\User;
use Spatie\LaravelData\Data;
use Spatie\Permission\Models\Role;

class NotificationRequestDto extends Data
{
    public function __construct(
        public readonly int $senderId,
        public readonly ?string $receiverEmail,
        public readonly string $department,
        public readonly string $type,
        public readonly string $content,
    )
    {}
    public static function fromArray(array $data): self
    {
        return new self(
            senderId: $data['senderId'],
            receiverEmail: $data['receiverEmail'] ?? null,
            department: $data['department'],
            type: $data['type'],
            content: $data['content'],
        );
    }
    public function toModel(): Notification
    {
        $receiver = User::where('email', $this->receiverEmail)->first() ?? null;
        $department = Role::where('name', $this->department)->firstOrFail();

        return new Notification([
            'sender_id' => $this->senderId,
            'receiver_id' => $receiver?->id,
            'department_id' => $department->id,
            'type' => $this->type,
            'content' => $this->content,
        ]);
    }
}
