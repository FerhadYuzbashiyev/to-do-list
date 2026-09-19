<?php

namespace App\DTO;

use App\Http\Requests\Task\CreateTaskRequest;

class CreateTaskData
{
    public function __construct(
        public string $title,
        public string $description,
    ) 
    {}

    public static function fromArray(CreateTaskRequest $data): self
    {
        return new self(
            title: $data['title'],
            description: $data['description'],
        );
    }
}