<?php

namespace App\Services;

use App\Exceptions\ShopLocationNotFoundException;
use App\Models\Message;
use App\Repositories\MessageRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
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

    /**
     * @param array $attributes
     * @return Message[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|Builder[]|Collection
     * @throws ShopLocationNotFoundException
     */
    public function search(array $attributes)
    {
        $message = $this->messageRepository->findByPhoneNumberAndSendAt($attributes);

        if (empty($message)) {
            throw new ModelNotFoundException();
        }

        if (empty($message->shop) or empty($message->shop->location)) {
            throw new ShopLocationNotFoundException();
        }

        return $this->messageRepository->getInfected($message);
    }
}
