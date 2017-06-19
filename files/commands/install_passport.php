<?php

// Intall
composer require laravel/passport

// Put in the list of providers
Laravel\Passport\PassportServiceProvider::class,

// verify 
php artisan

// execute some migrations for passport
php artisan migration

// intall passport commands
php artisan passport:install

// In Model User
use HasApiTokens,

// in autserviceprovider put
Passport::routes();


// Instead of use only thottle ir made the ouauth/token make use of the api middleware group
Route::post('oauth/token', '\Laravel\Passport\Http\Controllers\AccessTokenController@issueToken');



// in auth change the driver

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        'api' => [
            'driver' => 'passport',
            'provider' => 'users',
        ],
    ],

// Protect the controller  
    'client.credentials' => \Laravel\Passport\Http\Middleware\CheckClientCredentials::class, 


    public function __construct()
    {
        $this->middleware('client.credentials')->only(['index', 'show']);
    }

// create credentials
	php artisan passport:client

	Client ID: 3
	Client secret: iWQ3OYApmFfYfco9ydBEJHsZolnlDsIj35tX9h2F

// Make a post request to 
	POST - restfulapi.dev/oauth/token

	{
		grant_type : client_credentials
		client_id:  3
		client_secret: iWQ3OYApmFfYfco9ydBEJHsZolnlDsIj35tX9h2F
	}
// Response of this

{
    "token_type": "Bearer",
    "expires_in": 1800,
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6ImYyODgxYzk0NTZlODJiNGQwOTQ1OGMwMWU4MTAzNWE4ZGIwZGE3MzY1OTM3N2I0MTM0YjY2NjUwZTFkYmVhZmVmOGRiMjdhNzEyMGRlYTFjIn0.eyJhdWQiOiIzIiwianRpIjoiZjI4ODFjOTQ1NmU4MmI0ZDA5NDU4YzAxZTgxMDM1YThkYjBkYTczNjU5Mzc3YjQxMzRiNjY2NTBlMWRiZWFmZWY4ZGIyN2E3MTIwZGVhMWMiLCJpYXQiOjE0OTc4MjQyODMsIm5iZiI6MTQ5NzgyNDI4MywiZXhwIjoxNDk3ODI2MDgzLCJzdWIiOiIiLCJzY29wZXMiOltdfQ.PPNM9l1KtqqAVOpGltJRnwOqJAKYQWBt7MVVoWqj9Hitzz-unYFvbpVkRHvkJYbBiliyiTjgKWao7gOZLM1DOLTA5FKfyZ8b2sFX2NEnahpNhxxXUiB-MxDlHbSo7JmzLkU2yj-fUgRp_Ulh4ZAggYUpSrKbvUudyPN2PS1GBC0YCBysVy7dnT0usnRhx95dW8WrfuE0zNdTUeSAQinUbUItoUZMF8Yy801uxVGd1PpVk9dsRkIIhEzab-x0fGxjzCSVmn4ZbjklSHAJdubychDIt8xXa1UIX-4b-56zsCdahzoYnV5qofcmGUxo5eTPDO3id4iqEfnF7_n6D_nLZPwxqIyQeSIdrQ-oTgEdX4JIYPVwyrmb0kvISNxiZA7mE_zESixZqNoWeSWFlr_VUPN3QUQ0Q7_H_HmWJHdK6FQ5Wbu56A9qqlGnj3fPWK9ZQgLx1QXUBCsO17XRnLjb7xtLhkxNQdT8u2vDzLecWx825rIxIWfe9E-7GdD03pz6spBZ7mhFVRaCDi_cojKbvBHYOx_wTwux1UOHi3NIYm-TUXw_Q2uR-kMKO4q5oylCjFMCixgREP7rh44LZ9uTa96YeA_NFgWYcJAilTRdU83qqvZLaYB-G4bujxdv0OBkYqrn0EfOtZnFEjkX6wuZhPSyJ7ooy62WViLNMvAEAhs"
}

// Try to access:
	GET - restfulapi.dev/categories

// Response
{
    "error": "Unauthenticated.",
    "code": 401
}

// we need to send our acess_token in heder to authenticate the user

{
	Authorization : Bearer eyJ0eXAiOiJKV1QiL........
}

// and then we will get the response

_____________________________________________

// now we are going to create another client with password grant type

$ php artisan passport:client --password
Client ID: 4
Client Secret: y4JPi01qfwZf4GqgOBsovUF31nLyGvWhHXwpjlAi

// Make a post request to 
	POST - restfulapi.dev/oauth/token

        'grant_type' => 'password',
        'client_id' => '4',
        'client_secret' => 'y4JPi01qfwZf4GqgOBsovUF31nLyGvWhHXwpjlAi',
        'username' => 'jaden.hudson@example.net',
        'password' => 'secret',
        'scope' => '',
// response
{
    "token_type": "Bearer",
    "expires_in": 1800,
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjdiMTY0M2NlMzkzYmIxZTM4NGQxNDZlNjc3NTI0OTVlM2I3MGJiOGVhMDEzNTJiYTc0ODhiZmZkODQ4M2U1ODc4OTY5MDQzODkyNzdmNmExIn0.eyJhdWQiOiI0IiwianRpIjoiN2IxNjQzY2UzOTNiYjFlMzg0ZDE0NmU2Nzc1MjQ5NWUzYjcwYmI4ZWEwMTM1MmJhNzQ4OGJmZmQ4NDgzZTU4Nzg5NjkwNDM4OTI3N2Y2YTEiLCJpYXQiOjE0OTc4MzU3NTMsIm5iZiI6MTQ5NzgzNTc1MywiZXhwIjoxNDk3ODM3NTUzLCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.bc5BBkwI8h_9dfhU87XVOZsDEdfVjFRJ2SCvlRZfgnlh2_WgDEFjpvaBs2dW5r-zpmkXc9L25OcFY6ml6YFjplHoVz7-C5ZT_9uWoe4zeGZeo7W0J_AvaxOEtRqC-yBsP9JjMsR-BHYjvljJGhoeyeFjNupxK_0anu0n_0laCNIPO-3MDCp0uH0-fHBqGuMCiRA5kZ_Y_AUsBsdJZ2EEwApIWdUP43kFpBFH5VtD2T0ZF9eufuUutzYd2SVEqpATEBowP-2JhWTx2TuZm8BxtHOI1fXGC51lAgRfi0x4aBK14_qHLjhKZnxIjbI3WwS9JM-gvZrcmaJXodoVWv5f-4VB5aZAInqtJ5NaJb4h8TD6EFKIPIZW9iBNXc6g5iq0HoUsIX3DQrw88TiesRmRdaRinUVCWqlL8VtZ3_sVA8YMbM8WzXDuexVDI90rAGPLdukC5jgj51EvKVxFjkmNUHSdNeMWoyhUOFLRAYxGCyb8XmG0LkgCPcPpxTedw9ahPI-MjAH1ivGih3U4vqjkV4roFB-aYYgXD8_U5ep323dJaRnHN5TQ4Tkk5XuBpgdm6XuopDWjGhRjn9wp1pt05Hruwhoih9gylwaXvti0CTYY5CQAI-clYpxQHF0_f0XBd33XEYOvDHDD8_cQl35kV-Dln2itRTgHb2WVJs-EmFg",
    "refresh_token": "kd+DS3j7iKF9VxLC3I8fISqRLPmoBlZhY+ePAPLluBBdfhwdrF7KYxoV23NOzxbB3DXiGvIBbu+NGvJEY67vaAhVjIUWcaGYIrO9xq0S7iMYwPS0Wk8Ewy2F4199qluhp7E0G26O5goOJ7nMu//i70CE0z+W4rwUnYCjc00c0GtRXE3WGxurPlaDEuoichZBtePqGwZg1YSu6PbqIxCGLqi8jgDbWjLmEKXt4AnpOsQzj8AZ39v7GhzsFbHWkJ3ZIshFAAvkGGL98ElifBuuGrLS7uc6vIj8/4UFyqSAZpFBve3eUrb7Sc7xoFON6ZENhgd3qE6oE8N/NcCQB2N6Q4KXc1I9kVC40Q3vxxbHK1xnadKGvQs7Ajpbs7L+lQ5QLixAFwOpHJRDYjcktCzXJ+Yn3k6Uzg0V/JUclwS1tqRXeB33jGwJH1yYb+4PRCEKmS0wRytU35kVrcxdDof9uHC3SsECQsVlg27iJhsAQ/bbPv+LISJfL3DGP2vAVn33xP09QAVkJ6Nsgyvd0HgGrKxkU6QVHIPd+uuRiuxZCL4NVWHOT5Q5aFLKSTe7y6E877Yp5F3KxAklehZykWe5WvodYq/Y62f+VPI7k2GCC76Vo7mHOa1k0cZYO2Wo15uMNQpMpNl4iz0vjrXn6l9VcBW0TdT+8PSrNmUwL6YcBUc="
}

        
