<?php

namespace App\DTO\Task;

use App\Http\Requests\Task\UpdateTaskRequest;

class UpdateTaskData
{
    public function __construct(
        public ?string $title,
        public ?string $description,
    ) 
    {}

    public static function fromArray(UpdateTaskRequest $data): self
    {
        return new self(
            title: $data['title'] ?? null,
            description: $data['description'] ?? null,
        );
    }
    
    public function toArray(): array
    {
        return array_filter([
            'title' => $this->title,
            'description' => $this->description,
        ], fn($v) => $v !== null);
    }
}