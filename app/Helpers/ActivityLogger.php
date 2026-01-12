<?php

namespace App\Helpers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Request;

class ActivityLogger
{
    /**
     * Log an activity
     */
    public static function log($type, $action, $description, $properties = null)
    {
        try {
            ActivityLog::create([
                'user_id' => auth()->id(),
                'type' => $type,
                'action' => $action,
                'description' => $description,
                'properties' => $properties,
                'ip_address' => Request::ip(),
                'user_agent' => Request::userAgent(),
            ]);
        } catch (\Exception $e) {
            // Silently fail to not disrupt user experience
            \Log::error('Failed to log activity: ' . $e->getMessage());
        }
    }

    /**
     * Log authentication events
     */
    public static function logAuth($action, $description, $userId = null)
    {
        self::log('authentication', $action, $description, ['user_id' => $userId]);
    }

    /**
     * Log admin actions
     */
    public static function logAdmin($action, $description, $properties = null)
    {
        self::log('admin', $action, $description, $properties);
    }

    /**
     * Log booking events
     */
    public static function logBooking($action, $description, $bookingId, $properties = null)
    {
        $data = array_merge(['booking_id' => $bookingId], $properties ?? []);
        self::log('booking', $action, $description, $data);
    }

    /**
     * Log payment events
     */
    public static function logPayment($action, $description, $properties = null)
    {
        self::log('payment', $action, $description, $properties);
    }
}