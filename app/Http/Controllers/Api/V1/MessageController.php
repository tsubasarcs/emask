<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMessageRequest;
use App\Repositories\ShopRepository;
use App\Services\MessageService;
use Illuminate\Http\Response;

class MessageController extends Controller
{
    protected ShopRepository $shopRepository;
    protected MessageService $messageService;

    /**
     * @param ShopRepository $shopRepository
     * @param MessageService $messageService
     */
    public function __construct(ShopRepository $shopRepository, MessageService $messageService)
    {
        $this->shopRepository = $shopRepository;
        $this->messageService = $messageService;
    }

    /**
     * @param StoreMessageRequest $request
     * @return Response
     */
    public function store(StoreMessageRequest $request): Response
    {
        $shop = $this->shopRepository->findBy($request->input('text'), 'code');

        $attributes = array_merge($request->only(['time', 'from']), ['shop_id' => $shop->id]);

        $this->messageService->store($attributes);

        return response()->noContent();
    }
}
