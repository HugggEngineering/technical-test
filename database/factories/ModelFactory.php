<?php
use Carbon\Carbon;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker, $attributes) {
    $faker->addProvider(new \Tests\Utils\FakeUKPhoneNumber($faker));

    $number = $attributes['phone_number'] ?? $faker->ukPhoneNumber;
    $firstName = $attributes['first_name'] ?? $faker->firstName;
    $lastName = $attributes['last_name'] ?? $faker->lastName;

    return [
        'first_name' => $firstName,
        'surname' => $lastName,
        'email' => $faker->email,
        'phone_number' => $number,
    ];
});

$factory->define(App\Hug::class, function (Faker\Generator $faker, $attributes) {
    return [
        'sender_id' => $attributes['sender_id'] ?? factory(App\User::class)->create()->id,
        'receiver_id' => $attributes['receiver_id'] ?? factory(App\User::class)->create()->id,
        'purchase_id' => $attributes['purchase_id'] ?? factory(App\Price::class)->create()->id,
    ];
});

$factory->define(App\Brand::class, function (Faker\Generator $faker, $attributes) {
    return [
        'name' => $attributes['name'] ?? $faker->company,
    ];
});

$factory->define(App\Price::class, function (Faker\Generator $faker, $attributes) {
    return [
        'brand_id' => $attributes['brand_id'] ?? function () {
            return factory(App\Brand::class)->create()->id;
        },
        'image' => '',
        'description' => $attributes['description'] ?? $faker->text(100),
        'label' => $faker->word,
        'list_price' => '150',
        'sale_price' => '150',
        'subtitle' => $attributes['subtitle'] ?? $faker->word
    ];
});

$factory->define(App\Message::class, function (Faker\Generator $faker, $attr) {
    return [
        'hug_id' => $attr['hug_id'] ?? '',
        'message' => $attr['message'] ?? $faker->text(20),
    ];
});
