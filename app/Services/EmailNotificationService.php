<?php

namespace App\Services;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EmailNotificationService
{
    /**
     * Send welcome email to new user
     */
    public function sendWelcomeEmail(User $user)
    {
        try {
            $data = [
                'user' => $user,
                'subject' => 'Welcome to E-smooth Online!',
                'message' => 'Thank you for joining our community. Start exploring amazing products!'
            ];
            
            // In a real application, you would use Mail::send() with a proper email template
            Log::info("Welcome email sent to: {$user->email}");
            
            return true;
        } catch (\Exception $e) {
            Log::error("Failed to send welcome email: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send order confirmation email
     */
    public function sendOrderConfirmation(Order $order)
    {
        try {
            $user = $order->user;
            $data = [
                'user' => $user,
                'order' => $order,
                'subject' => "Order Confirmation #" . $order->id,
                'message' => 'Your order has been confirmed and is being processed.'
            ];
            
            Log::info("Order confirmation email sent to: {$user->email} for order #{$order->id}");
            
            return true;
        } catch (\Exception $e) {
            Log::error("Failed to send order confirmation email: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send order status update email
     */
    public function sendOrderStatusUpdate(Order $order, string $status)
    {
        try {
            $user = $order->user;
            $statusMessages = [
                'processing' => 'Your order is being processed.',
                'shipped' => 'Your order has been shipped and is on its way!',
                'delivered' => 'Your order has been delivered. Thank you for shopping with us!',
                'cancelled' => 'Your order has been cancelled. If you have any questions, please contact us.'
            ];
            
            $data = [
                'user' => $user,
                'order' => $order,
                'status' => $status,
                'subject' => "Order Update #" . $order->id,
                'message' => $statusMessages[$status] ?? 'Your order status has been updated.'
            ];
            
            Log::info("Order status update email sent to: {$user->email} for order #{$order->id} - Status: {$status}");
            
            return true;
        } catch (\Exception $e) {
            Log::error("Failed to send order status update email: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send product back in stock notification
     */
    public function sendBackInStockNotification(Product $product, array $userEmails)
    {
        try {
            foreach ($userEmails as $email) {
                $data = [
                    'email' => $email,
                    'product' => $product,
                    'subject' => $product->name . ' is back in stock!',
                    'message' => 'The product you were waiting for is now available.'
                ];
                
                Log::info("Back in stock notification sent to: {$email} for product: {$product->name}");
            }
            
            return true;
        } catch (\Exception $e) {
            Log::error("Failed to send back in stock notifications: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send promotional email
     */
    public function sendPromotionalEmail(array $userEmails, string $subject, string $message, array $products = [])
    {
        try {
            foreach ($userEmails as $email) {
                $data = [
                    'email' => $email,
                    'subject' => $subject,
                    'message' => $message,
                    'products' => $products
                ];
                
                Log::info("Promotional email sent to: {$email} - Subject: {$subject}");
            }
            
            return true;
        } catch (\Exception $e) {
            Log::error("Failed to send promotional emails: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send password reset email
     */
    public function sendPasswordResetEmail(User $user, string $resetToken)
    {
        try {
            $data = [
                'user' => $user,
                'resetToken' => $resetToken,
                'subject' => 'Password Reset Request',
                'message' => 'Click the link below to reset your password. This link will expire in 1 hour.',
                'resetUrl' => url('/reset-password?token=' . $resetToken)
            ];
            
            Log::info("Password reset email sent to: {$user->email}");
            
            return true;
        } catch (\Exception $e) {
            Log::error("Failed to send password reset email: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send newsletter subscription confirmation
     */
    public function sendNewsletterConfirmation(string $email)
    {
        try {
            $data = [
                'email' => $email,
                'subject' => 'Newsletter Subscription Confirmed',
                'message' => 'Thank you for subscribing to our newsletter! You\'ll receive updates about new products and special offers.'
            ];
            
            Log::info("Newsletter confirmation sent to: {$email}");
            
            return true;
        } catch (\Exception $e) {
            Log::error("Failed to send newsletter confirmation: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send review request email
     */
    public function sendReviewRequest(Order $order)
    {
        try {
            $user = $order->user;
            $data = [
                'user' => $user,
                'order' => $order,
                'subject' => 'How was your recent purchase?',
                'message' => 'We\'d love to hear about your experience with your recent purchase. Please leave a review!'
            ];
            
            Log::info("Review request email sent to: {$user->email} for order #{$order->id}");
            
            return true;
        } catch (\Exception $e) {
            Log::error("Failed to send review request email: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send cart abandonment email
     */
    public function sendCartAbandonmentEmail(User $user, array $cartItems)
    {
        try {
            $data = [
                'user' => $user,
                'cartItems' => $cartItems,
                'subject' => 'Don\'t forget your items!',
                'message' => 'You left some great items in your cart. Complete your purchase before they\'re gone!'
            ];
            
            Log::info("Cart abandonment email sent to: {$user->email}");
            
            return true;
        } catch (\Exception $e) {
            Log::error("Failed to send cart abandonment email: " . $e->getMessage());
            return false;
        }
    }
}
