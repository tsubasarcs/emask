<?php

namespace App\Listeners;

use App\Repositories\ShopRepository;
use App\Services\MessageService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Arr;

class StoreMessage implements ShouldQueue
{
    protected MessageService $messageService;
    protected ShopRepository $shopRepository;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(MessageService $messageService, ShopRepository $shopRepository)
    {
        $this->messageService = $messageService;
        $this->shopRepository = $shopRepository;
    }

    /**
     * Handle the event.
     *
     * @param object $event
     * @return void
     */
    public function handle(object $event): void
    {
        $shop = $this->shopRepository->findBy($event->inputs['text'], 'code');

        $attributes = array_merge(Arr::only($event->inputs, ['time', 'from']), ['shop_id' => $shop->id]);

        $this->messageService->store($attributes);
    }
}
