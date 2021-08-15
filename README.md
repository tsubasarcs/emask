# EMask

## local environment

Recommended to use [Laravel Sail](https://laravel.com/docs/8.x/sail), you can refer to the "docker-compose.yml" file under the directory.
needing:

- php8.
- mysql8.
- redis.

## Installation

The project used redis, please set `CACHE_DRIVER=redis` in your `.env` file.

The project used queue, please set `QUEUE_CONNECTION=redis` in your `.env` file.

The project used [Google's geocoding service](https://developers.google.com/maps/documentation/geocoding/start), please get api key from your own Google Cloud Platform, and set `GOOGLE_MAPS_GEOCODING_API_KEY` in your `.env` file.

## API

### [POST] /api/v1/shops

generate shop code from address.

#### header
```
Accept: appliaction/json
Content-Type: appliaction/json
```

#### request
```
{
    "address": "台北市松山區民權東路三段106巷3弄5號7樓"
}
```

#### response(200)
```
{
    "status": 1,
    "data": {
        "code": "890950546400095"
    }
}
```

#### response(422)
```
{
    "message": "The given data was invalid.",
    "errors": {
        "address": [
            "地址必須填寫"
        ]
    }
}
```

---

### [POST] /api/v1/messages

SMS record.

#### header
```
Accept: appliaction/json
Content-Type: appliaction/json
```

#### request
```
{
    "time": "2021-01-01T00:00:00",
    "from": "0912345678",
    "text": "場所代碼：1111 1111 1111 111\n本簡訊是簡訊實聯制發送，限防疫目的使用
}
```

#### response(204)

---

### [POST] /api/v1/messages/search

Search point distance sphere 50m and 7days of infected.

#### header
```
Accept: appliaction/json
Content-Type: appliaction/json
```

#### request
```
{
    "time": "2021-01-01T00:00:00",
    "from": "0912345678"
}
```

#### response(200)

```
{
    "status": 1,
    "data": [
        {
            "phone_number": "0912345678",
            "shop_address": "台北市松山區民權東路三段106巷3弄5號7樓",
            "shop_code": "890950546400095",
            "send_at": "2021-01-01T00:00:00"
        }
    ]
}
```

#### response(400)

```
{
    "status": 0,
    "data": []
}
```
---
