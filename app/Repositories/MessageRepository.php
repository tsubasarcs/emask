<?php

namespace App\Repositories;

use App\Models\Message;

class MessageRepository
{
    protected Message $model;

    /**
     * @param Message $message
     */
    public function __construct(Message $message)
    {
        $this->model = $message;
    }

    /**
     * @param array $attributes
     * @return Message
     */
    public function create(array $attributes): Message
    {
        return $this->model->create($attributes);
    }
}
