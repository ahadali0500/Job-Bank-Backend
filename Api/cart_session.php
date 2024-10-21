<?php
require 'vendor/autoload.php';
$stripe = new \Stripe\StripeClient('sk_test_51P8HdbJUuIKY8I25Gn7SSuXYklcZiEdhDcE5KjhgQDkS7DlmanGsuMhNherSPFXP65DbFDlzeTC4Ff5l8R0VnVQs00xJl8vRw9');

// Use an existing Customer ID if this is a returning customer.
$amount = $_POST['amount'];
$customer = $stripe->customers->create();
$ephemeralKey = $stripe->ephemeralKeys->create([
  'customer' => $customer->id,
], [
  'stripe_version' => '2024-04-10',
]);
$paymentIntent = $stripe->paymentIntents->create([
  'amount' => $amount,
  'currency' => 'eur',
  'customer' => $customer->id,
  // In the latest version of the API, specifying the `automatic_payment_methods` parameter
  // is optional because Stripe enables its functionality by default.
  'automatic_payment_methods' => [
    'enabled' => 'true',
  ],
]);

echo json_encode(
  [
    'paymentIntent' => $paymentIntent->client_secret,
    'ephemeralKey' => $ephemeralKey->secret,
    'customer' => $customer->id,
    'publishableKey' => 'pk_test_51P8HdbJUuIKY8I258ECQRr9u6AIWfqB2Y4Hx0IxqH8eHij7EiBZP2L6JLO6vbTA6DG58NQpz8XOL7JXWJlHUbf1L00YfbjvS9I'
  ]
);
http_response_code(200);