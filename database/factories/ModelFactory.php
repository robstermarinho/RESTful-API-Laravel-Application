<?php

use App\User;
use App\Seller;
use App\Product;
use App\Category;
use App\Transaction;

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

/** @var \Illuminate\Database\Eloquent\Factory $factory */

/**
 * User Factory
 *
 * The verifivation_token variable is created to generate a valid token for authenticate the email
 */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
        'verified' => $verified = $faker->randomElement([User::VERIFIED_USER, User::UNVERIFIED_USER]),
        'verification_token' => $verified == User::VERIFIED_USER ? null : User::generateVerificationCode(),
        'admin' => $faker->randomElement([User::ADMIN_USER, User::REGULAR_USER]),
    ];
});

/**
 * Category Factory
 */
$factory->define(Category::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->word,
        'description' => $faker->paragraph(1),
    ];
});

/**
 * Product Factory
 */
$factory->define(Product::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->word,
        'description' => $faker->paragraph(1),
        'quantity' => $faker->numberBetween(1, 10),
        'status' => $faker->randomElement([Product::AVAILABLE_PRODUCT, Product::UNAVAILABLE_PRODUCT]),
        'image' => $faker->randomElement(['1.png', '2.png', '3.png', '4.png']),
        'seller_id' => User::all()->random()->id,				// Every User can be a seller of this product
        														// It transforms him in a seller
        // User::inRandomOrder()->first()->id
    ];
});


/**
 * Transaction Factory
 * 
 * A Seller is someone who really has at least one product
 * Every User can be a buyer at a moment
 */

$factory->define(Transaction::class, function (Faker\Generator $faker) {

	$seller = Seller::has('products')->get()->random();			// Get a random seller who has products
	$buyer = User::all()->except($seller->id)->random();		// Get a random buyer different from the seller
    return [
        'quantity' => $faker->numberBetween(1, 3),
        'buyer_id' => $buyer->id,								// The buyer id
        'product_id' => $seller->products->random()->id,		// And the id of one product of the seller
        // User::inRandomOrder()->first()->id
    ];
});
