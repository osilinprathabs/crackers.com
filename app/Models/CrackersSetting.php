<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrackersSetting extends Model
{
    use HasFactory;

    protected $table = 'crackers_settings';

    protected $fillable = [
        'company_name',
        'gst_percentage',
        'enable_cod',
        'enable_upi',
        'upi_id',
        'upi_qr_code',
        'enable_bank_transfer',
        'bank_name',
        'account_number',
        'ifsc_code',
        'account_holder',
        'support_phone',
        'support_email',
        'support_address',
        'support_hours',
        'company_slogan',
        'license_number',
        'supreme_court_disclaimer',
        'google_map_embed',
        'terms_and_conditions',
        'privacy_policy',
        'shipping_policy',
    ];

    protected $casts = [
        'gst_percentage' => 'decimal:2',
        'enable_cod' => 'boolean',
        'enable_upi' => 'boolean',
        'enable_bank_transfer' => 'boolean',
    ];

    public static function getSettings()
    {
        $settings = static::firstOrCreate([], [
            'gst_percentage' => 18.00,
            'enable_cod' => true,
            'enable_upi' => true,
            'upi_id' => 'crackers@upi',
            'enable_bank_transfer' => true,
            'bank_name' => 'State Bank of India',
            'account_number' => '123456789012',
            'ifsc_code' => 'SBIN0001234',
            'account_holder' => 'S.R. TRADERS',
            'support_phone' => '+91 98765 43210',
            'support_email' => 'support@crackers.com',
            'support_address' => 'S.R. TRADERS, Main Bazaar Road, Sivakasi, Tamil Nadu 626123',
            'support_hours' => 'Mon - Sun: 8:00 AM - 10:00 PM',
            'company_slogan' => 'Lighting Up Your Celebrations with 100% Safe & Certified Crackers!',
            'license_number' => 'LE/5/1234/2026',
            'supreme_court_disclaimer' => 'As per 2018 Supreme Court order, online sale of firecrackers are not permitted! We value our customers and at the same time, respect jurisdiction. We request you to add your products to the cart and submit the required crackers through the enquiry button. We will contact you within 24 hrs and confirm the order through WhatsApp or phone call. Please add and submit your enquiries and enjoy your Diwali with S.R.TRADERS. Our License No. LE/5/1234/2026. S.R.TRADERS as a company following 100% legal & statutory compliances and all our shops, go-downs are maintained as per the explosive acts. We send the parcels through registered and legal transport service providers as like every other major companies in Sivakasi is doing so.',
            'google_map_embed' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3936.574483782977!2d77.7946927!3d9.4533036!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3b06cee3a4e107f9%3A0xd1469e38ef8bfcfd!2sSivakasi%2C%20Tamil%20Nadu!5e0!3m2!1sen!2sin!4v1700000000000!5m2!1sen!2sin',
            'terms_and_conditions' => "Welcome to Crackers.com. All purchases are governed by standard explosive & fireworks transport safety guidelines. Customers must be 18 years or older to purchase festive fireworks.",
            'privacy_policy' => "Crackers.com values your privacy. We collect customer delivery details solely for processing festive order dispatches. We do not sell or disclose your personal data to third parties.",
            'shipping_policy' => "All orders are dispatched via registered surface transport safely packaged according to safety standards. Delivery timelines vary from 2 to 5 business days depending on city location.",
        ]);

        if (empty($settings->company_name)) {
            $settings->company_name = 'S.R. TRADERS';
            $settings->save();
        }

        if (empty($settings->supreme_court_disclaimer)) {
            $compName = $settings->company_name ?: 'S.R. TRADERS';
            $licNo = $settings->license_number ?: 'LE/5/1234/2026';
            $settings->supreme_court_disclaimer = "As per 2018 Supreme Court order, online sale of firecrackers are not permitted! We value our customers and at the same time, respect jurisdiction. We request you to add your products to the cart and submit the required crackers through the enquiry button. We will contact you within 24 hrs and confirm the order through WhatsApp or phone call. Please add and submit your enquiries and enjoy your Diwali with {$compName}. Our License No. {$licNo}. {$compName} as a company following 100% legal & statutory compliances and all our shops, go-downs are maintained as per the explosive acts. We send the parcels through registered and legal transport service providers as like every other major companies in Sivakasi is doing so.";
            $settings->save();
        }

        if (empty($settings->company_slogan)) {
            $settings->company_slogan = 'Lighting Up Your Celebrations with 100% Safe & Certified Crackers!';
            $settings->save();
        }

        if (empty($settings->license_number)) {
            $settings->license_number = 'LE/5/1234/2026';
            $settings->save();
        }

        return $settings;
    }
}
