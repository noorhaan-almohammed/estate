<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Requests\TransactionRequest;

class TransactionController extends Controller
{

    /**
     * Store a newly created transaction in the database.
     *
     * This method handles the creation of a new transaction using the validated data
     * from the request. It returns a JSON response with the created transaction data.
     *
     * @param \App\Http\Requests\TransactionRequest $request The validated request object containing transaction data.
     * @return \Illuminate\Http\JsonResponse A JSON response containing the created transaction and a 201 status code.
     */
    public function store(TransactionRequest $request)
    {
        // Validate the incoming request and retrieve the validated data.
        // The TransactionRequest handles the validation logic before reaching this method.
        $data = $request->validated();

        // Create a new transaction record in the database using the validated data.
        $transaction = Transaction::create($data);

        // Return a JSON response with the created transaction and a 201 status code.
        // 201 status code indicates that the resource has been created successfully.
        return response()->json($transaction, 201);
    }
}
