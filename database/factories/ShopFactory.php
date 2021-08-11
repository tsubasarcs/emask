<?php

namespace Database\Factories;

use App\Models\Shop;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class ShopFactory extends Factory
{
    protected array $convenience_store_address = [
        '台北市松山區民權東路三段108號',
        '台北市松山區民權東路三段160巷9號1樓',
        '台北市松山區民權東路三段103巷15-1號',
        '台北市松山區民權東路三段106巷11號',
        '台北市松山區民權東路三段160巷11號',
        '台北市松山區民權東路三段104號壹樓及地下室'
    ];

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Shop::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'code' => str_pad((string)rand(0, 999999999999999), 15, "0", STR_PAD_LEFT),
            'address' => Arr::random($this->convenience_store_address),
        ];
    }
}
