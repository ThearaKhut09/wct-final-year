<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class PaymentGatewayService
{
    private $apiKey;
    private $apiSecret;
    private $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('payment.api_key', 'test_api_key');
        $this->apiSecret = config('payment.api_secret', 'test_api_secret');
        $this->baseUrl = config('payment.base_url', 'https://api.stripe.com/v1');
    }

    /**
     * Process credit card payment
     */
    public function processCreditCardPayment(Order $order, array $cardData)
    {
        try {
            // Simulate payment processing
            $paymentData = [
                'amount' => $order->total_amount * 100, // Convert to cents
                'currency' => 'usd',
                'source' => [
                    'number' => $cardData['card_number'],
                    'exp_month' => $cardData['exp_month'],
                    'exp_year' => $cardData['exp_year'],
                    'cvc' => $cardData['cvc']
                ],
                'description' => 'Order #' . $order->id,
                'metadata' => [
                    'order_id' => $order->id,
                    'customer_email' => $order->customer->email
                ]
            ];

            // Simulate successful payment (in real app, would call actual payment gateway)
            $paymentResult = $this->simulatePaymentResponse($paymentData);

            if ($paymentResult['status'] === 'succeeded') {
                // Record payment in database
                $payment = Payment::create([
                    'order_id' => $order->id,
                    'payment_method' => 'credit_card',
                    'payment_gateway' => 'stripe',
                    'transaction_id' => $paymentResult['transaction_id'],
                    'amount' => $order->total_amount,
                    'status' => 'completed',
                    'gateway_response' => json_encode($paymentResult)
                ]);

                // Update order status
                $order->update([
                    'payment_status' => 'paid',
                    'order_status' => 'processing'
                ]);

                Log::info("Payment successful for order #{$order->id}", [
                    'transaction_id' => $paymentResult['transaction_id'],
                    'amount' => $order->total_amount
                ]);

                return [
                    'success' => true,
                    'transaction_id' => $paymentResult['transaction_id'],
                    'payment' => $payment
                ];
            } else {
                throw new Exception($paymentResult['error_message'] ?? 'Payment failed');
            }

        } catch (Exception $e) {
            Log::error("Payment failed for order #{$order->id}: " . $e->getMessage());
            
            // Record failed payment
            Payment::create([
                'order_id' => $order->id,
                'payment_method' => 'credit_card',
                'payment_gateway' => 'stripe',
                'amount' => $order->total_amount,
                'status' => 'failed',
                'error_message' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Process PayPal payment
     */
    public function processPayPalPayment(Order $order, string $paypalPaymentId)
    {
        try {
            // Simulate PayPal payment verification
            $paymentResult = $this->simulatePayPalResponse($paypalPaymentId, $order->total_amount);

            if ($paymentResult['status'] === 'approved') {
                $payment = Payment::create([
                    'order_id' => $order->id,
                    'payment_method' => 'paypal',
                    'payment_gateway' => 'paypal',
                    'transaction_id' => $paymentResult['transaction_id'],
                    'amount' => $order->total_amount,
                    'status' => 'completed',
                    'gateway_response' => json_encode($paymentResult)
                ]);

                $order->update([
                    'payment_status' => 'paid',
                    'order_status' => 'processing'
                ]);

                Log::info("PayPal payment successful for order #{$order->id}");

                return [
                    'success' => true,
                    'transaction_id' => $paymentResult['transaction_id'],
                    'payment' => $payment
                ];
            } else {
                throw new Exception('PayPal payment not approved');
            }

        } catch (Exception $e) {
            Log::error("PayPal payment failed for order #{$order->id}: " . $e->getMessage());
            
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Process refund
     */
    public function processRefund(Payment $payment, float $amount = null)
    {
        try {
            $refundAmount = $amount ?? $payment->amount;
            
            // Simulate refund processing
            $refundResult = $this->simulateRefundResponse($payment->transaction_id, $refundAmount);

            if ($refundResult['status'] === 'succeeded') {
                // Create refund record
                $refund = Payment::create([
                    'order_id' => $payment->order_id,
                    'payment_method' => $payment->payment_method,
                    'payment_gateway' => $payment->payment_gateway,
                    'transaction_id' => $refundResult['refund_id'],
                    'amount' => -$refundAmount, // Negative amount for refund
                    'status' => 'completed',
                    'gateway_response' => json_encode($refundResult),
                    'parent_payment_id' => $payment->id
                ]);

                Log::info("Refund successful for payment #{$payment->id}", [
                    'refund_id' => $refundResult['refund_id'],
                    'amount' => $refundAmount
                ]);

                return [
                    'success' => true,
                    'refund_id' => $refundResult['refund_id'],
                    'refund' => $refund
                ];
            } else {
                throw new Exception('Refund processing failed');
            }

        } catch (Exception $e) {
            Log::error("Refund failed for payment #{$payment->id}: " . $e->getMessage());
            
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Create payment intent for frontend
     */
    public function createPaymentIntent(Order $order)
    {
        try {
            $intentData = [
                'amount' => $order->total_amount * 100,
                'currency' => 'usd',
                'payment_method_types' => ['card'],
                'metadata' => [
                    'order_id' => $order->id
                ]
            ];

            // Simulate payment intent creation
            $clientSecret = 'pi_' . uniqid() . '_secret_' . uniqid();

            Log::info("Payment intent created for order #{$order->id}");

            return [
                'success' => true,
                'client_secret' => $clientSecret,
                'publishable_key' => config('payment.publishable_key', 'pk_test_example')
            ];

        } catch (Exception $e) {
            Log::error("Failed to create payment intent: " . $e->getMessage());
            
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Verify webhook signature
     */
    public function verifyWebhookSignature(string $payload, string $signature)
    {
        $expectedSignature = hash_hmac('sha256', $payload, $this->apiSecret);
        
        return hash_equals($expectedSignature, $signature);
    }

    /**
     * Handle webhook events
     */
    public function handleWebhookEvent(array $event)
    {
        try {
            switch ($event['type']) {
                case 'payment_intent.succeeded':
                    $this->handlePaymentSuccess($event['data']['object']);
                    break;
                
                case 'payment_intent.payment_failed':
                    $this->handlePaymentFailure($event['data']['object']);
                    break;
                
                case 'charge.dispute.created':
                    $this->handleDispute($event['data']['object']);
                    break;
                
                default:
                    Log::info("Unhandled webhook event: " . $event['type']);
            }

            return true;

        } catch (Exception $e) {
            Log::error("Webhook processing failed: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Simulate payment gateway responses for demo purposes
     */
    private function simulatePaymentResponse(array $paymentData)
    {
        // Simulate different scenarios based on card number
        $cardNumber = $paymentData['source']['number'] ?? '';
        
        if (str_contains($cardNumber, '4000000000000002')) {
            // Simulate declined card
            return [
                'status' => 'failed',
                'error_message' => 'Your card was declined.'
            ];
        } elseif (str_contains($cardNumber, '4000000000000119')) {
            // Simulate processing error
            return [
                'status' => 'failed',
                'error_message' => 'A processing error occurred.'
            ];
        } else {
            // Simulate successful payment
            return [
                'status' => 'succeeded',
                'transaction_id' => 'txn_' . uniqid(),
                'amount' => $paymentData['amount'],
                'currency' => $paymentData['currency']
            ];
        }
    }

    private function simulatePayPalResponse(string $paymentId, float $amount)
    {
        return [
            'status' => 'approved',
            'transaction_id' => 'pp_' . uniqid(),
            'amount' => $amount,
            'payer_email' => 'customer@example.com'
        ];
    }

    private function simulateRefundResponse(string $transactionId, float $amount)
    {
        return [
            'status' => 'succeeded',
            'refund_id' => 'ref_' . uniqid(),
            'amount' => $amount,
            'original_transaction' => $transactionId
        ];
    }

    private function handlePaymentSuccess($paymentIntent)
    {
        $orderId = $paymentIntent['metadata']['order_id'] ?? null;
        if ($orderId) {
            $order = Order::find($orderId);
            if ($order) {
                $order->update(['payment_status' => 'paid']);
                Log::info("Payment webhook: Order #{$orderId} marked as paid");
            }
        }
    }

    private function handlePaymentFailure($paymentIntent)
    {
        $orderId = $paymentIntent['metadata']['order_id'] ?? null;
        if ($orderId) {
            $order = Order::find($orderId);
            if ($order) {
                $order->update(['payment_status' => 'failed']);
                Log::info("Payment webhook: Order #{$orderId} marked as payment failed");
            }
        }
    }

    private function handleDispute($dispute)
    {
        // Handle payment disputes
        Log::warning("Payment dispute created", ['dispute_id' => $dispute['id']]);
    }
}
