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
                'user_agent' => substr(Request::userAgent(), 0, 255),
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to log activity: ' . $e->getMessage());
        }
    }

    /**
     * Log authentication events (login, logout, failed attempts)
     */
    public static function logAuth($action, $description, $userId = null)
    {
        self::log('authentication', $action, $description, ['target_user_id' => $userId]);
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

    /**
     * Log profile updates
     */
    public static function logProfile($action, $description, $properties = null)
    {
        self::log('profile', $action, $description, $properties);
    }

    /**
     * Log menu operations (for caterers)
     */
    public static function logMenu($action, $description, $menuId = null, $properties = null)
    {
        $data = $menuId ? array_merge(['menu_id' => $menuId], $properties ?? []) : $properties;
        self::log('menu', $action, $description, $data);
    }

    /**
     * Log security events
     */
    public static function logSecurity($action, $description, $properties = null)
    {
        self::log('security', $action, $description, $properties);
    }
}