<?php

use App\User;
use App\Product;
use App\Category;
use App\Transaction;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       	// Disable Foreign Key Checks
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        // We have to truncate all tables (original state)
        User::truncate();
        Category::truncate();
        Product::truncate();
        Transaction::truncate();
        DB::table('category_product')->truncate();

        $usersQuantity = 1000;
        $categoriesQuantity = 30;
        $productsQuantity = 1000;
        $transactionsQuantity = 1000;

        factory(User::class, $usersQuantity)->create();
        factory(Category::class, $categoriesQuantity)->create();

        

        factory(Product::class, $productsQuantity)
        ->create()->each(	// For each created product				
        	function ($product) {
        		// select between 1 to 5 random categories from a list of categories
        		$categories = Category::all()->random(mt_rand(1, 5)) 
        		->pluck('id'); // but I want to get only the ids

        		// atach the categories to a product
        		$product->categories()->attach($categories);
        });
        factory(Transaction::class, $transactionsQuantity)->create();


    }
}
