<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class BuyerController extends ApiController
{
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
    public function show($id)
    {
        // They are users that have transactions
        $buyer = Buyer::has('transactions')->findOrFail($id);
        //return response()->json(['data' => $buyer], 200);
        return $this->showOne($buyer);
    }

}
