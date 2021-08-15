<?php

namespace App\Repositories;

use App\Models\Message;
use Grimzy\LaravelMysqlSpatial\Eloquent\Builder as SpatialBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

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

    /**
     * @param array $attributes
     * @return Message|Builder|Model|object|null
     */
    public function findByPhoneNumberAndSendAt(array $attributes)
    {
        return $this
            ->model
            ->with('shop')
            ->where('phone_number', '=', $attributes['phone_number'])
            ->whereDate('send_at', $attributes['send_at'])
            ->first();
    }

    /**
     * @param Message $message
     * @param int $range_days
     * @param int $distance
     * @return Message[]|Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder[]|Collection
     */
    public function getInfected(Message $message, int $range_days = 7, int $distance = 50)
    {
        return $this->model::with('shop')
            ->whereHas('shop', function (SpatialBuilder $query) use ($message, $distance) {
                $query->distanceSphere('location', $message->shop->location, $distance);
            })
            ->whereBetween('send_at', [$message->send_at->subDays($range_days), $message->send_at])
            ->get();
    }
}
