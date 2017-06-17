<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    /**
     * A general APIcontroller to extends other controller usingm my ApiResponser traits
     *
     */
    use ApiResponser;
}
