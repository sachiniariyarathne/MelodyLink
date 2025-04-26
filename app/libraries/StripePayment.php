<?php
// Fix the autoloader path
require_once dirname(dirname(__DIR__)) . '/vendor/autoload.php';

class StripePayment {
    private $stripe;
    
    public function __construct() {
        try {
            // For testing, use the key directly
            $secretKey = 'sk_test_51Qe7HeA97EM7XCbcspqvSrF0TQsK7tbt13Qs0KktLHkGB4b1HONCqEueeMUTThJrVwIMAF2K2p5lVVVAOxksk4CK0026Ull7bJ';
            
            if (empty($secretKey)) {
                throw new Exception('Stripe secret key is not configured');
            }
            
            \Stripe\Stripe::setApiKey($secretKey);
            $this->stripe = new \Stripe\StripeClient($secretKey);
            
            // Test the connection
            $this->stripe->customers->all(['limit' => 1]);
            
        } catch (Exception $e) {
            error_log('Stripe initialization error: ' . $e->getMessage());
            throw $e;
        }
    }
    
    public function createPaymentIntent($amount) {
        try {
            error_log('Creating payment intent for amount: ' . $amount);
            
            // Convert amount to cents (Stripe uses smallest currency unit)
            $amountInCents = $amount * 100;
            
            error_log('Amount in cents: ' . $amountInCents);
            
            $paymentIntent = $this->stripe->paymentIntents->create([
                'amount' => $amountInCents,
                'currency' => 'lkr',
                'payment_method_types' => ['card'],
                'metadata' => [
                    'integration_check' => 'accept_a_payment'
                ]
            ]);
            
            error_log('Payment intent created successfully: ' . $paymentIntent->id);
            
            return [
                'success' => true,
                'paymentIntentId' => $paymentIntent->id
            ];
        } catch (\Stripe\Exception\ApiErrorException $e) {
            error_log('Stripe API error: ' . $e->getMessage());
            error_log('Stripe API error type: ' . get_class($e));
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        } catch (Exception $e) {
            error_log('Payment intent creation error: ' . $e->getMessage());
            error_log('Error type: ' . get_class($e));
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    public function confirmPayment($paymentIntentId) {
        try {
            $paymentIntent = $this->stripe->paymentIntents->retrieve($paymentIntentId);
            
            return [
                'success' => true,
                'status' => $paymentIntent->status,
                'paymentIntent' => $paymentIntent
            ];
        } catch (\Stripe\Exception\ApiErrorException $e) {
            error_log('Stripe API error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
} 