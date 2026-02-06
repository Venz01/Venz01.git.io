<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use App\Models\Booking;
use App\Models\Review;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Create a notification for a user
     */
    public function create($userId, $type, $title, $message, $data = [])
    {
        return Notification::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
        ]);
    }

    /**
     * Notify customer about new booking
     */
    public function notifyBookingCreated(Booking $booking)
    {
        return $this->create(
            $booking->customer_id,
            'booking_created',
            'Booking Created Successfully',
            "Your booking #{$booking->booking_number} has been created and is pending caterer approval.",
            [
                'booking_id' => $booking->id,
                'url' => route('customer.booking.details', $booking->id),
            ]
        );
    }

    /**
     * Notify caterer about new booking
     */
    public function notifyCatererNewBooking(Booking $booking)
    {
        return $this->create(
            $booking->caterer_id,
            'booking_pending',
            'New Booking Request',
            "New booking request #{$booking->booking_number} from {$booking->customer_name} for {$booking->event_date->format('M d, Y')}",
            [
                'booking_id' => $booking->id,
                'url' => route('caterer.booking.details', $booking->id),
            ]
        );
    }

    /**
     * Notify customer when booking is confirmed
     */
    public function notifyBookingConfirmed(Booking $booking)
    {
        return $this->create(
            $booking->customer_id,
            'booking_confirmed',
            'Booking Confirmed!',
            "Great news! Your booking #{$booking->booking_number} has been confirmed by the caterer.",
            [
                'booking_id' => $booking->id,
                'url' => route('customer.booking.details', $booking->id),
            ]
        );
    }

    /**
     * Notify customer when booking is rejected
     */
    public function notifyBookingRejected(Booking $booking)
    {
        return $this->create(
            $booking->customer_id,
            'booking_rejected',
            'Booking Declined',
            "Unfortunately, your booking #{$booking->booking_number} has been declined by the caterer.",
            [
                'booking_id' => $booking->id,
                'url' => route('customer.booking.details', $booking->id),
            ]
        );
    }

    /**
     * Notify customer when booking is completed
     */
    public function notifyBookingCompleted(Booking $booking)
    {
        return $this->create(
            $booking->customer_id,
            'booking_completed',
            'Booking Completed',
            "Your event for booking #{$booking->booking_number} has been marked as completed. Please leave a review!",
            [
                'booking_id' => $booking->id,
                'url' => route('customer.review.create', $booking->id),
            ]
        );
    }

    /**
     * Notify caterer about payment received
     */
    public function notifyPaymentReceived(Booking $booking, $type = 'deposit')
    {
        $message = $type === 'deposit' 
            ? "Deposit payment received for booking #{$booking->booking_number}" 
            : "Full balance payment received for booking #{$booking->booking_number}";

        return $this->create(
            $booking->caterer_id,
            'payment_received',
            'Payment Received',
            $message,
            [
                'booking_id' => $booking->id,
                'url' => route('caterer.booking.details', $booking->id),
            ]
        );
    }

    /**
     * Notify customer about balance due
     */
    public function notifyBalanceDue(Booking $booking)
    {
        return $this->create(
            $booking->customer_id,
            'balance_due',
            'Balance Payment Due',
            "Your event is coming up! Please pay the remaining balance of ₱" . number_format($booking->balance, 2) . " for booking #{$booking->booking_number}",
            [
                'booking_id' => $booking->id,
                'url' => route('customer.booking.pay-balance', $booking->id),
            ]
        );
    }

    /**
     * Notify caterer about new review
     */
    public function notifyReviewReceived($review)
    {
        return $this->create(
            $review->caterer_id,
            'review_received',
            'New Review Received',
            "{$review->customer->name} left you a {$review->rating}-star review on booking #{$review->booking->booking_number}",
            [
                'review_id' => $review->id,
                'booking_id' => $review->booking_id,
                'url' => route('caterer.reviews'),
            ]
        );
    }

    /**
     * Notify customer about caterer response to review
     */
    public function notifyReviewResponse($review)
    {
        return $this->create(
            $review->customer_id,
            'review_response',
            'Caterer Responded to Your Review',
            "{$review->caterer->business_name} responded to your review on booking #{$review->booking->booking_number}",
            [
                'review_id' => $review->id,
                'booking_id' => $review->booking_id,
                'url' => route('customer.booking.details', $review->booking_id),
            ]
        );
    }

    /**
     * Send warning notification to caterer about flagged/removed review
     * 
     * This creates both an in-app notification AND sends an email
     *
     * @param Review $review
     * @param string $reason
     * @return void
     */
    public function notifyCatererWarning(Review $review, string $reason)
    {
        try {
            $caterer = $review->caterer;
            
            if (!$caterer) {
                Log::warning('Cannot send caterer warning - caterer not found', [
                    'review_id' => $review->id
                ]);
                return;
            }

            // Create in-app notification
            $this->create(
                $caterer->id,
                'review_warning',
                '⚠️ Review Warning - Action Required',
                "A review has been flagged/removed by admin. Reason: {$reason}",
                [
                    'review_id' => $review->id,
                    'booking_id' => $review->booking_id,
                    'reason' => $reason,
                    'url' => route('caterer.reviews'),
                ]
            );

            // Send email notification if caterer has email
            if ($caterer->email) {
                try {
                    Mail::send('emails.caterer-review-warning', [
                        'caterer' => $caterer,
                        'review' => $review,
                        'reason' => $reason,
                        'customer_name' => $review->customer->name ?? 'A customer',
                        'review_date' => $review->created_at->format('F d, Y'),
                    ], function ($message) use ($caterer) {
                        $message->to($caterer->email, $caterer->name)
                                ->subject('⚠️ Warning: Review Flagged/Removed - Action Required');
                    });

                    Log::info('Caterer warning email sent', [
                        'review_id' => $review->id,
                        'caterer_id' => $caterer->id,
                        'caterer_email' => $caterer->email,
                    ]);
                } catch (\Exception $e) {
                    Log::error('Failed to send caterer warning email', [
                        'review_id' => $review->id,
                        'caterer_id' => $caterer->id,
                        'error' => $e->getMessage()
                    ]);
                    // Don't throw - notification was created, email is secondary
                }
            }

        } catch (\Exception $e) {
            Log::error('Failed to send caterer warning notification', [
                'review_id' => $review->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Re-throw to be caught by controller
            throw $e;
        }
    }

    /**
     * Get unread count for a user
     */
    public function getUnreadCount($userId)
    {
        return Notification::where('user_id', $userId)
            ->unread()
            ->count();
    }

    /**
     * Mark all notifications as read for a user
     */
    public function markAllAsRead($userId)
    {
        return Notification::where('user_id', $userId)
            ->unread()
            ->update(['read_at' => now()]);
    }

    /**
     * Delete old notifications (older than 30 days)
     */
    public function deleteOldNotifications()
    {
        return Notification::where('created_at', '<', now()->subDays(30))
            ->delete();
    }
}