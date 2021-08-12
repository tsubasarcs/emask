<?php

namespace App\Services;

use App\Models\Message;
use App\Repositories\MessageRepository;
use Illuminate\Support\Str;

class MessageService
{
    protected MessageRepository $messageRepository;

    /**
     * @param MessageRepository $messageRepository
     */
    public function __construct(MessageRepository $messageRepository)
    {
        $this->messageRepository = $messageRepository;
    }

    /**
     * @param array $attributes
     * @return Message
     */
    public function store(array $attributes): Message
    {
        $attributes = [
            'shop_id' => $attributes['shop_id'],
            'phone_number' => $this->normalizePhoneNumber($attributes['from']),
            'send_at' => $attributes['time']
        ];

        return $this->messageRepository->create($attributes);
    }

    /**
     * @param string $number
     * @return array|string|string[]|null
     */
    public function normalizePhoneNumber(string $number): string
    {
        if (Str::startsWith($number, '(+886)')) {
            return Str::replace('(+886)', '0', $number);
        }

        return preg_replace('/[^0-9]/', '', $number);
    }
}
