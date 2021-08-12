<?php

namespace Tests\Feature\app\HTTP\Controllers\Api\V1;

use App\Foundations\Faker\Geocoder as FakerGeocoder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Spatie\Geocoder\Facades\Geocoder;
use Str;
use Tests\TestCase;

class ShopControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->faker->addProvider($this->app->make(FakerGeocoder::class));
    }

    /**
     * @test
     * @group ShopController
     */
    public function it_should_create_shop_record_success()
    {
        $address = '台北市松山區民權東路三段104號壹樓及地下室';

        Geocoder::shouldReceive('getCoordinatesForAddress')
            ->once()
            ->with($address)
            ->andReturn($this->faker->getSuccessResponse);

        $response = $this->postJson(route('api.v1.shops.post'), [
            'address' => $address
        ]);

        $response
            ->assertOk()
            ->assertJsonStructure([
                'status',
                'data' => [
                    'code',
                ]
            ]);

        $this->assertDatabaseHas('shops', [
            'address' => $address,
        ]);
    }

    /**
     * @test
     * @group ShopController
     */
    public function it_should_return_422_when_address_input_is_empty()
    {
        $response = $this->postJson(route('api.v1.shops.post'), [
            'address' => ''
        ]);

        $response
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'address',
                ]
            ]);

        $this->assertDatabaseMissing('shops', [
            'address' => '',
        ]);
    }

    /**
     * @test
     * @group ShopController
     */
    public function it_should_return_422_when_address_input_length_over_255_char()
    {
        $address = Str::random(256);

        Geocoder::shouldReceive('getCoordinatesForAddress')
            ->once()
            ->with($address)
            ->andReturn($this->faker->getFailedResponse);

        $response = $this->postJson(route('api.v1.shops.post'), [
            'address' => $address,
        ]);

        $response
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'address',
                ]
            ]);

        $this->assertDatabaseMissing('shops', [
            'address' => $address,
        ]);
    }

    /**
     * @test
     * @group ShopController
     */
    public function it_should_return_422_when_address_input_is_invalid()
    {
        $address = Str::random(10);

        Geocoder::shouldReceive('getCoordinatesForAddress')
            ->once()
            ->with($address)
            ->andReturn($this->faker->getFailedResponse);

        $response = $this->postJson(route('api.v1.shops.post'), [
            'address' => $address,
        ]);

        $response
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'geocoder',
                ]
            ]);

        $this->assertDatabaseMissing('shops', [
            'address' => $address,
        ]);
    }
}
