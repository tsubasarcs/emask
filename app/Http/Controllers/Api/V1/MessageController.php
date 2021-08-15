<?php

namespace App\Http\Controllers\Api\V1;

use App\Events\MessageCreating;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMessageRequest;
use App\Services\MessageService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MessageController extends Controller
{
    protected MessageService $messageService;

    /**
     * @param MessageService $messageService
     */
    public function __construct(MessageService $messageService)
    {
        $this->messageService = $messageService;
    }

    /**
     * @param StoreMessageRequest $request
     * @return Response
     */
    public function store(StoreMessageRequest $request): Response
    {
        event(new MessageCreating($request->all()));

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
