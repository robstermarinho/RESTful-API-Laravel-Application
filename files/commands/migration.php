<?php

// Creating a table to make a relationship many to many 
//between category and product

php artisan make:migration category_product_table --create=category_product

// After some modification on migrations we can refresh and seed the tables
php artisan migrate:refresh --seed

