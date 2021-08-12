<?php

namespace Tests\Feature\app\Http\Controllers\Api\V1;

use App\Models\Shop;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class MessageControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @group MessageController
     */
    public function it_should_store_record_success()
    {
        Carbon::setTestNow('2021-08-13 00:00:00');

        $shop = Shop::factory()->create(['code' => '111111111111111']);

        $phone_number = '0912-345-678';

        $form_params = [
            'time' => Carbon::now()->format('Y-m-d\TH:i:s'),
            'from' => $phone_number,
            'text' => '場所代碼：1111 1111 1111 111\n本簡訊是簡訊實聯制發送，限防疫目的使用',
        ];

        $this
            ->postJson(route('api.v1.messages.post'), $form_params)
            ->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertDatabaseHas('messages', [
            'shop_id' => $shop->id,
            'phone_number' => '0912345678',
            'send_at' => Carbon::now()->toDateTimeString(),
        ]);
    }

    /**
     * @test
     * @group MessageController
     */
    public function it_should_store_record_success_with_another_phone_number()
    {
        Carbon::setTestNow('2021-08-13 00:00:00');

        $shop = Shop::factory()->create(['code' => '111111111111111']);

        $phone_number = '(+886)912345678';

        $form_params = [
            'time' => Carbon::now()->format('Y-m-d\TH:i:s'),
            'from' => $phone_number,
            'text' => '場所代碼：1111 1111 1111 111\n本簡訊是簡訊實聯制發送，限防疫目的使用',
        ];

        $this
            ->postJson(route('api.v1.messages.post'), $form_params)
            ->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertDatabaseHas('messages', [
            'shop_id' => $shop->id,
            'phone_number' => '0912345678',
            'send_at' => Carbon::now()->toDateTimeString(),
        ]);
    }

    /**
     * @test
     * @group MessageController
     */
    public function it_should_validate_time_need_to_be_required()
    {
        Carbon::setTestNow('2021-08-13 00:00:00');

        $shop = Shop::factory()->create(['code' => '111111111111111']);

        $phone_number = '0912345678';

        $form_params = [
            'time' => '',
            'from' => $phone_number,
            'text' => '場所代碼：1111 1111 1111 111\n本簡訊是簡訊實聯制發送，限防疫目的使用',
        ];

        $this
            ->postJson(route('api.v1.messages.post'), $form_params)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'time',
                ]
            ]);

        $this->assertDatabaseMissing('messages', [
            'shop_id' => $shop->id,
            'phone_number' => $phone_number,
            'send_at' => Carbon::now()->toDateTimeString(),
        ]);
    }

    /**
     * @test
     * @group MessageController
     */
    public function it_should_validate_time_need_to_be_string()
    {
        Carbon::setTestNow('2021-08-13 00:00:00');

        $shop = Shop::factory()->create(['code' => '111111111111111']);

        $phone_number = '0912345678';

        $form_params = [
            'time' => 123456,
            'from' => $phone_number,
            'text' => '場所代碼：1111 1111 1111 111\n本簡訊是簡訊實聯制發送，限防疫目的使用',
        ];

        $this
            ->postJson(route('api.v1.messages.post'), $form_params)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'time',
                ]
            ]);

        $this->assertDatabaseMissing('messages', [
            'shop_id' => $shop->id,
            'phone_number' => $phone_number,
            'send_at' => Carbon::now()->toDateTimeString(),
        ]);
    }

    /**
     * @test
     * @group MessageController
     */
    public function it_should_validate_time_need_to_be_specific_format()
    {
        Carbon::setTestNow('2021-08-13 00:00:00');

        $shop = Shop::factory()->create(['code' => '111111111111111']);

        $phone_number = '0912345678';

        $form_params = [
            'time' => Carbon::now()->toDateTimeString(),
            'from' => $phone_number,
            'text' => '場所代碼：1111 1111 1111 111\n本簡訊是簡訊實聯制發送，限防疫目的使用',
        ];

        $this
            ->postJson(route('api.v1.messages.post'), $form_params)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'time',
                ]
            ]);

        $this->assertDatabaseMissing('messages', [
            'shop_id' => $shop->id,
            'phone_number' => $phone_number,
            'send_at' => Carbon::now()->toDateTimeString(),
        ]);
    }

    /**
     * @test
     * @group MessageController
     */
    public function it_should_validate_from_need_to_be_required()
    {
        Carbon::setTestNow('2021-08-13 00:00:00');

        $shop = Shop::factory()->create(['code' => '111111111111111']);

        $phone_number = '0912345678';

        $form_params = [
            'time' => Carbon::now()->format('Y-m-d\TH:i:s'),
            'from' => '',
            'text' => '場所代碼：1111 1111 1111 111\n本簡訊是簡訊實聯制發送，限防疫目的使用',
        ];

        $this
            ->postJson(route('api.v1.messages.post'), $form_params)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'from',
                ]
            ]);

        $this->assertDatabaseMissing('messages', [
            'shop_id' => $shop->id,
            'phone_number' => $phone_number,
            'send_at' => Carbon::now()->toDateTimeString(),
        ]);
    }

    /**
     * @test
     * @group MessageController
     */
    public function it_should_validate_from_need_to_be_string()
    {
        Carbon::setTestNow('2021-08-13 00:00:00');

        $shop = Shop::factory()->create(['code' => '111111111111111']);

        $phone_number = '0912345678';

        $form_params = [
            'time' => Carbon::now()->format('Y-m-d\TH:i:s'),
            'from' => 123123123,
            'text' => '場所代碼：1111 1111 1111 111\n本簡訊是簡訊實聯制發送，限防疫目的使用',
        ];

        $this
            ->postJson(route('api.v1.messages.post'), $form_params)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'from',
                ]
            ]);

        $this->assertDatabaseMissing('messages', [
            'shop_id' => $shop->id,
            'phone_number' => $phone_number,
            'send_at' => Carbon::now()->toDateTimeString(),
        ]);
    }

    /**
     * @test
     * @group MessageController
     */
    public function it_should_validate_from_need_to_be_specific_format()
    {
        Carbon::setTestNow('2021-08-13 00:00:00');

        $shop = Shop::factory()->create(['code' => '111111111111111']);

        $phone_number = '0912345678';

        $form_params = [
            'time' => Carbon::now()->format('Y-m-d\TH:i:s'),
            'from' => '09-12-34-56-78',
            'text' => '場所代碼：1111 1111 1111 111\n本簡訊是簡訊實聯制發送，限防疫目的使用',
        ];

        $this
            ->postJson(route('api.v1.messages.post'), $form_params)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'from',
                ]
            ]);

        $this->assertDatabaseMissing('messages', [
            'shop_id' => $shop->id,
            'phone_number' => $phone_number,
            'send_at' => Carbon::now()->toDateTimeString(),
        ]);
    }

    /**
     * @test
     * @group MessageController
     */
    public function it_should_validate_text_need_to_be_required()
    {
        Carbon::setTestNow('2021-08-13 00:00:00');

        $shop = Shop::factory()->create(['code' => '111111111111111']);

        $phone_number = '0912345678';

        $form_params = [
            'time' => Carbon::now()->format('Y-m-d\TH:i:s'),
            'from' => $phone_number,
            'text' => '',
        ];

        $this
            ->postJson(route('api.v1.messages.post'), $form_params)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'text',
                ]
            ]);

        $this->assertDatabaseMissing('messages', [
            'shop_id' => $shop->id,
            'phone_number' => $phone_number,
            'send_at' => Carbon::now()->toDateTimeString(),
        ]);
    }

    /**
     * @test
     * @group MessageController
     */
    public function it_should_validate_text_need_to_be_string()
    {
        Carbon::setTestNow('2021-08-13 00:00:00');

        $shop = Shop::factory()->create(['code' => '111111111111111']);

        $phone_number = '0912345678';

        $form_params = [
            'time' => Carbon::now()->format('Y-m-d\TH:i:s'),
            'from' => $phone_number,
            'text' => 12381028319,
        ];

        $this
            ->postJson(route('api.v1.messages.post'), $form_params)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'text',
                ]
            ]);

        $this->assertDatabaseMissing('messages', [
            'shop_id' => $shop->id,
            'phone_number' => $phone_number,
            'send_at' => Carbon::now()->toDateTimeString(),
        ]);
    }

    /**
     * @test
     * @group MessageController
     */
    public function it_should_validate_text_need_to_be_specific_format()
    {
        Carbon::setTestNow('2021-08-13 00:00:00');

        $shop = Shop::factory()->create(['code' => '111111111111111']);

        $phone_number = '0912345678';

        $form_params = [
            'time' => Carbon::now()->format('Y-m-d\TH:i:s'),
            'from' => $phone_number,
            'text' => '「本簡訊是簡訊實聯制發送，限防疫目的使用」',
        ];

        $this
            ->postJson(route('api.v1.messages.post'), $form_params)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'text',
                ]
            ]);

        $this->assertDatabaseMissing('messages', [
            'shop_id' => $shop->id,
            'phone_number' => $phone_number,
            'send_at' => Carbon::now()->toDateTimeString(),
        ]);
    }
}
