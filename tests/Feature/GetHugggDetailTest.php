<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Log;

use Tests\TestCase;
use Tests\Feature\TestHelper;
use App\User;
use App\Brand;
use App\Price;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class GetHugggDetailTest extends TestCase
{
    // use DatabaseMigrations;
    public function setUp()
    {
        parent::setUp();
    }

    public function testEmbeddedDetailsReturned()
    {
        $user = factory(\App\User::class)->create();
        $huggg = factory(\App\Hug::class)->create(['receiver_id' => $user->id]);

        TestHelper::setupFakeSessionWithUser($user, [ 'user' => true ]);

        $response = $this->json('GET', '/api/v2/huggg/get/' . $huggg->id);
        $response = $response->decodeResponseJson();

        $this->assertTrue(is_array($response['hug']));
        $this->assertTrue(is_array($response['data']));
        $this->assertTrue(is_array($response['data']['purchase']['brand']['locations']));
        $this->assertTrue(is_array($response['data']['purchase']['brand']['stores']));

        foreach ($response['data']['purchase']['brand']['locations'] as $store) {
            $this->assertTrue(
                in_array($store['id']),
                $response['data']['purchase']['brand']['stores']
            );
        }

        // older apps expect the huggg to be returned as "hug"
        $this->assertEquals($response['data']['id'], $response['hug']['id']);
    }
}
