<?php

namespace Tests\Feature\app\Http\Controllers\Api\V1;

use App\Models\Message;
use App\Models\Shop;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Spatie\Geocoder\Facades\Geocoder;
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

    /**
     * @test
     * @group MessageControllerSearch
     */
    public function it_should_searched_messages_in_50m()
    {
        Carbon::setTestNow('2021-08-16 00:00:00');

        $shop1 = Shop::factory()->create([
            'code' => '111111111111111',
            'address' => '台北市松山區民權東路三段106巷3弄5號7樓',
            'location' => new Point(25.061595, 121.545145, 4326),
        ]);

        $shop2 = Shop::factory()->create([
            'code' => '222222222222222',
            'address' => '台北市松山區民權東路三段108號',
            'location' => new Point(25.0618581, 121.5450194, 4326),
        ]);

        $shop3 = Shop::factory()->create([
            'code' => '333333333333333',
            'address' => '台北市中山區建國北路一段96號4樓',
            'location' => new Point(25.0504431, 121.5360737, 4326),
        ]);

        $message1 = Message::factory()->create([
            'shop_id' => $shop1->id,
            'send_at' => Carbon::now(),
        ]);

        $message2 = Message::factory()->create([
            'shop_id' => $shop1->id,
            'send_at' => Carbon::now()->subDays(10),
        ]);

        $message3 = Message::factory()->create([
            'shop_id' => $shop2->id,
            'send_at' => Carbon::now()->subDays(5),
        ]);

        $message4 = Message::factory()->create([
            'shop_id' => $shop2->id,
            'send_at' => Carbon::now()->subDays(20),
        ]);

        $message5 = Message::factory()->create([
            'shop_id' => $shop3->id,
            'send_at' => Carbon::now(),
        ]);

        $message6 = Message::factory()->create([
            'shop_id' => $shop3->id,
            'send_at' => Carbon::now()->subDays(15),
        ]);

        $form_params = [
            'time' => $message1->send_at,
            'from' => $message1->phone_number,
        ];

        $response = $this
            ->postJson(route('api.v1.messages.search'), $form_params)
            ->assertOK()
            ->assertJsonStructure([
                'status',
                'data',
            ]);

        $content = json_decode($response->getContent(), true);

        $this->assertCount(2, $content);

        $this->assertEquals($content['data'][0]['shop_code'], $shop1->code);
        $this->assertEquals($content['data'][1]['shop_code'], $shop2->code);
    }

    /**
     * @test
     * @group MessageControllerSearch
     */
    public function it_should_searched_empty_message()
    {
        Carbon::setTestNow('2021-08-16 00:00:00');

        $shop1 = Shop::factory()->create([
            'code' => '111111111111111',
            'address' => '台北市松山區民權東路三段106巷3弄5號7樓',
            'location' => new Point(25.061595, 121.545145, 4326),
        ]);

        $shop2 = Shop::factory()->create([
            'code' => '222222222222222',
            'address' => '台北市松山區民權東路三段108號',
            'location' => new Point(25.0618581, 121.5450194, 4326),
        ]);

        $shop3 = Shop::factory()->create([
            'code' => '333333333333333',
            'address' => '台北市中山區建國北路一段96號4樓',
            'location' => new Point(25.0504431, 121.5360737, 4326),
        ]);

        $message1 = Message::factory()->create([
            'shop_id' => $shop1->id,
            'send_at' => Carbon::now(),
        ]);

        $message2 = Message::factory()->create([
            'shop_id' => $shop1->id,
            'send_at' => Carbon::now()->subDays(10),
        ]);

        $message3 = Message::factory()->create([
            'shop_id' => $shop2->id,
            'send_at' => Carbon::now()->subDays(5),
        ]);

        $message4 = Message::factory()->create([
            'shop_id' => $shop2->id,
            'send_at' => Carbon::now()->subDays(20),
        ]);

        $message5 = Message::factory()->create([
            'shop_id' => $shop3->id,
            'send_at' => Carbon::now(),
        ]);

        $message6 = Message::factory()->create([
            'shop_id' => $shop3->id,
            'send_at' => Carbon::now()->subDays(15),
        ]);

        $form_params = [
            'time' => Carbon::now(),
            'from' => '0912345678',
        ];

        $this
            ->postJson(route('api.v1.messages.search'), $form_params)
            ->assertOk()
            ->assertExactJson([
                'status' => \App\Foundations\Api\V1\Response::SUCCESS_STATUS,
                'data' => [],
            ]);
    }

    /**
     * @test
     * @group MessageControllerSearch
     */
    public function it_should_searched_failed()
    {
        Carbon::setTestNow('2021-08-16 00:00:00');

        $shop1 = Shop::factory()->create([
            'code' => '111111111111111',
            'address' => '台北市松山區民權東路三段106巷3弄5號7樓',
        ]);

        $shop2 = Shop::factory()->create([
            'code' => '222222222222222',
            'address' => '台北市松山區民權東路三段108號',
            'location' => new Point(25.0618581, 121.5450194, 4326),
        ]);

        $shop3 = Shop::factory()->create([
            'code' => '333333333333333',
            'address' => '台北市中山區建國北路一段96號4樓',
            'location' => new Point(25.0504431, 121.5360737, 4326),
        ]);

        $message1 = Message::factory()->create([
            'shop_id' => $shop1->id,
            'send_at' => Carbon::now(),
        ]);

        $message2 = Message::factory()->create([
            'shop_id' => $shop1->id,
            'send_at' => Carbon::now()->subDays(10),
        ]);

        $message3 = Message::factory()->create([
            'shop_id' => $shop2->id,
            'send_at' => Carbon::now()->subDays(5),
        ]);

        $message4 = Message::factory()->create([
            'shop_id' => $shop2->id,
            'send_at' => Carbon::now()->subDays(20),
        ]);

        $message5 = Message::factory()->create([
            'shop_id' => $shop3->id,
            'send_at' => Carbon::now(),
        ]);

        $message6 = Message::factory()->create([
            'shop_id' => $shop3->id,
            'send_at' => Carbon::now()->subDays(15),
        ]);

        $form_params = [
            'time' => $message1->send_at,
            'from' => $message1->phone_number,
        ];

        $this
            ->postJson(route('api.v1.messages.search'), $form_params)
            ->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertExactJson([
                'status' => \App\Foundations\Api\V1\Response::FAILED_STATUS,
                'data' => [],
            ]);
    }
}
