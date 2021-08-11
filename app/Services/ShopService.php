<?php

namespace App\Services;

use App\Models\Shop;
use App\Repositories\ShopRepository;

class ShopService
{
    /**
     * @var ShopRepository
     */
    protected ShopRepository $shopRepository;

    /**
     * @param ShopRepository $shopRepository
     */
    public function __construct(ShopRepository $shopRepository)
    {
        $this->shopRepository = $shopRepository;
    }

    /**
     * @param array $attributes
     * @return Shop
     */
    public function store(array $attributes): Shop
    {
        $code = $this->generateCode();

        $attributes = array_merge($attributes, ['code' => $code]);

        return $this->shopRepository->create($attributes);
    }

    /**
     * @return string
     */
    public function generateCode(): string
    {
        $code = str_pad((string)rand(0, 999999999999999), 15, "0", STR_PAD_LEFT);

        if ($this->shopRepository->exists($code, 'code')) {
            return $this->generateCode();
        }

        return $code;
    }
}
