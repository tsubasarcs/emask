<?php

namespace App\Repositories;

use App\Models\Shop;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

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

    /**
     * @param $attribute
     * @param string $column
     * @return Shop|Builder|Model
     */
    public function findBy($attribute, string $column = 'id'): Shop
    {
        return $this->model->where($column, '=', $attribute)->firstOrFail();
    }
}
