<?php

namespace App\Http\Controllers\Api\V1;

use App\Foundations\Api\V1\Response as ApiV1Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreShopRequest;
use App\Services\ShopService;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use Illuminate\Http\Response;

class ShopController extends Controller
{
    /**
     * @var ShopService
     */
    protected ShopService $shopService;

    /**
     * @param ShopService $shopService
     */
    public function __construct(ShopService $shopService)
    {
        $this->shopService = $shopService;
    }

    /**
     * @param StoreShopRequest $request
     * @return Response
     */
    public function store(StoreShopRequest $request): Response
    {
        $geocoder = $request->input('geocoder');

        $attributes = [
            'address' => $request->input('address'),
            'location' => new Point($geocoder['lat'], $geocoder['lng'], 4326),
        ];

        $shop = $this->shopService->store($attributes);

        return response([
            'status' => ApiV1Response::SUCCESS_STATUS,
            'data' => [
                'code' => $shop->code,
            ]
        ]);
    }
}
