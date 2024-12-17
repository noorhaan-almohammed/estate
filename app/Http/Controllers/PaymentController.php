<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Charge;

class PaymentController extends Controller
{
    /**
     * Display the payment form.
     *
     * This method returns the view that contains the payment form.
     */
    public function showPaymentForm()
    {
        return view('payment.form');
    }

    /**
     * Process the payment request.
     *
     * This method handles the payment process by receiving the token from Stripe and the amount
     * entered by the user, validating them, and creating a charge using the Stripe API.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function processPayment(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'amount' => 'required|numeric|min:1', // Amount must be a positive numeric value
            'token' => 'required|string',          // The Stripe token must be provided
        ]);

        // Set the Stripe API key using the secret key from the configuration
        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            // Create a charge using the Stripe API
            $charge = Charge::create([
                'amount' => $request->amount * 100, // Convert amount to cents (Stripe uses cents)
                'currency' => 'usd',                // Set the currency to USD
                'source' => $request->token,        // Use the token provided by the client
                'description' => 'دفع إلكتروني باستخدام Stripe', // Payment description
            ]);

            // Return a JSON response indicating success
            return response()->json([
                'success' => true,
                'message' => 'تم الدفع بنجاح!', // Payment successful message
                'data' => $charge,              // Include charge details in the response
            ], 200);
        } catch (\Exception $e) {
            // Handle any errors that occur during the payment process
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(), // Return the error message from Stripe
            ], 500);
        }
    }
}
