<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class BuyerController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // They are users that have transactions
        $buyers = Buyer::has('transactions')->get();
        // Without using a trait
        // return response()->json(['data' => $buyers], 200);
        return $this->showAll($buyers);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Buyer $buyer)
    {
        // They are users that have transactions
        //$buyer = Buyer::has('transactions')->findOrFail($id);
        
        return $this->showOne($buyer);
    }

}
