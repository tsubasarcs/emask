<?php

namespace App\Foundations\Faker;

use Faker\Provider\Base;

class Geocoder extends Base
{
    /**
     * @return array
     */
    public function getSuccessResponse(): array
    {
        return [
            'lat' => 25.0618166,
            'lng' => 121.5448278,
            'accuracy' => 'ROOFTOP',
            'formatted_address' => '105台灣台北市松山區民權東路三段104號壹 及',
            'viewport' => (object)[
                'northeast' => (object)[
                    'lat' => 25.063163530292,
                    'lng' => 121.54618828029,
                ],
                'southwest' => (object)[
                    'lat' => 25.060465569708,
                    'lng' => 121.54349031971,
                ]
            ],
            'address_components' => [
                (object)[
                    'long_name' => '壹 及',
                    'short_name' => '壹 及',
                    'types' => [
                        'subpremise',
                    ]
                ],
                (object)[
                    'long_name' => '104',
                    'short_name' => '104',
                    'types' => [
                        'street_number',
                    ]
                ],
                (object)[
                    'long_name' => '民權東路三段',
                    'short_name' => '民權東路三段',
                    'types' => [
                        'route',
                    ]
                ],
                (object)[
                    'long_name' => '民有里',
                    'short_name' => '民有里',
                    'types' => [
                        'administrative_area_level_4',
                        'political',
                    ]
                ],
                (object)[
                    'long_name' => '松山區',
                    'short_name' => '松山區',
                    'types' => [
                        'administrative_area_level_3',
                        'political',
                    ]
                ],
                (object)[
                    'long_name' => '台北市',
                    'short_name' => '台北市',
                    'types' => [
                        'administrative_area_level_1',
                        'political',
                    ]
                ],
                (object)[
                    'long_name' => '台灣',
                    'short_name' => '台灣',
                    'types' => [
                        'country',
                        'political',
                    ]
                ],
                (object)[
                    'long_name' => '105',
                    'short_name' => '105',
                    'types' => [
                        'postal_code',
                    ]
                ],
            ],
            'place_id' => 'ElXlo7kg5Y-KLCBOby4gMTA0LCBTZWN0aW9uIDMsIE1pbnF1YW4gRSBSZCwgU29uZ3NoYW4gRGlzdHJpY3QsIFRhaXBlaSBDaXR5LCBUYWl3YW4gMTA1IiMaIQoWChQKEgn1gKzE5KtCNBHgGWznarfgExIH5aO5IOWPig',
        ];
    }

    /**
     * @return array
     */
    public function getFailedResponse(): array
    {
        return [
            'lat' => 0,
            'lng' => 0,
            'accuracy' => 'result_not_found',
            'formatted_address' => 'result_not_found',
            'viewport' => 'result_not_found',
        ];
    }
}
