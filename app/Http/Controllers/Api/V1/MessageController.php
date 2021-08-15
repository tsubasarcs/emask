<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMessageRequest;
use App\Repositories\ShopRepository;
use App\Services\MessageService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
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

    /**
     * @param Request $request
     * @return Response
     */
    public function search(Request $request): Response
    {
        try {
            $messages = $this->messageService->search([
                'phone_number' => $this->messageService->normalizePhoneNumber($request->input('from')),
                'send_at' => $request->input('time'),
            ]);
        } catch (ModelNotFoundException $exception) {
            return response([
                'status' => \App\Foundations\Api\V1\Response::SUCCESS_STATUS,
                'data' => [],
            ]);
        } catch (Exception $exception) {
            return response([
                'status' => \App\Foundations\Api\V1\Response::FAILED_STATUS,
                'data' => [],
            ], Response::HTTP_BAD_REQUEST);
        }

        return response([
            'status' => \App\Foundations\Api\V1\Response::SUCCESS_STATUS,
            'data' => $messages->map(function ($message) {
                return [
                    'phone_number' => $message->phone_number,
                    'shop_address' => $message->shop->address,
                    'shop_code' => $message->shop->code,
                    'send_at' => $message->send_at->format('Y-m-d\TH:i:s'),
                ];
            })->unique()->values()->toArray(),
        ]);
    }
}
