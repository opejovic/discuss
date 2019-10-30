<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Channel;
use Faker\Generator as Faker;

$factory->define(Channel::class, function (Faker $faker) {
    $slug = $faker->word;
    return [
        'slug' => $slug,
        'name' => $slug
    ];
});
