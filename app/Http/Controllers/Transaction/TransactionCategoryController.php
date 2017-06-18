<?php

namespace App\Http\Controllers\Transaction;

use App\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class TransactionCategoryController extends ApiController
{
    /**
     * Display a listing of transactions for an especific category
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Transaction $transaction)
    {
        $categories = $transaction->product->categories;
        return $this->showAll($categories);
    }

   
}
