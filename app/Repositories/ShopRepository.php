<?php

namespace App\Repositories;

use App\Models\Shop;

class ShopRepository
{
    /**
     * @var Shop
     */
    protected Shop $model;

    /**
     * @param Shop $shop
     */
    public function __construct(Shop $shop)
    {
        $this->model = $shop;
    }

    /**
     * @param array $attributes
     * @return Shop
     */
    public function create(array $attributes): Shop
    {
        return $this->model->create($attributes);
    }

    /**
     * @param $attribute
     * @param string $column
     * @return bool
     */
    public function exists($attribute, string $column = 'id'): bool
    {
        return $this->model->where($column, '=', $attribute)->exists();
    }
}
