<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'title',
        'message',
        'link',
        'icon',
        'is_read',
        'read_at',
        'related_id',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    /**
     * Scope for unread notifications
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope for read notifications
     */
    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    /**
     * Scope for specific type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    /**
     * Mark notification as unread
     */
    public function markAsUnread()
    {
        $this->update([
            'is_read' => false,
            'read_at' => null,
        ]);
    }

    /**
     * Get icon class based on notification type
     */
    public function getIconClassAttribute()
    {
        if ($this->icon) {
            return $this->icon;
        }

        return match($this->type) {
            'new_order', 'online_order' => 'ri-shopping-cart-fill',
            'pos_order' => 'ri-store-2-fill',
            'pos_quotation' => 'ri-file-text-fill',
            'customer_registered', 'new_user_registration' => 'ri-user-add-fill',
            'order_dispatched' => 'ri-truck-fill',
            'order_delivered' => 'ri-checkbox-circle-fill',
            'order_cancelled' => 'ri-close-circle-fill',
            'payment_received', 'payment_confirmed' => 'ri-money-rupee-circle-fill',
            'emi_overdue' => 'ri-alarm-warning-fill',
            'new_loan_application' => 'ri-file-list-3-fill',
            default => 'ri-notification-3-fill',
        };
    }

    /**
     * Get badge color based on notification type
     */
    public function getBadgeColorAttribute()
    {
        return match($this->type) {
            'new_order', 'online_order' => 'warning',
            'pos_order' => 'info',
            'pos_quotation' => 'secondary',
            'customer_registered', 'new_user_registration' => 'primary',
            'order_dispatched' => 'info',
            'order_delivered' => 'success',
            'order_cancelled' => 'danger',
            'payment_received', 'payment_confirmed' => 'success',
            'emi_overdue' => 'danger',
            'new_loan_application' => 'primary',
            default => 'primary',
        };
    }
}
