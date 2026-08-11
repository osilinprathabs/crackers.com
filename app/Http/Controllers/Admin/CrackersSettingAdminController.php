<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CrackersSetting;
use App\Models\CrackersBankAccount;
use Illuminate\Support\Facades\Storage;

class CrackersSettingAdminController extends Controller
{
    public function edit()
    {
        $settings = CrackersSetting::getSettings();
        $bankAccounts = CrackersBankAccount::latest()->get();
        return view('admin.settings.payment', compact('settings', 'bankAccounts'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'gst_percentage' => 'required|numeric|min:0|max:100',
            'min_retail_order_amount' => 'nullable|numeric|min:0',
            'min_wholesale_order_amount' => 'nullable|numeric|min:0',
            'upi_id' => 'nullable|string|max:255',
            'upi_qr_code' => 'nullable|image|max:2048',
            'support_phone' => 'nullable|string|max:255',
            'support_email' => 'nullable|email|max:255',
            'support_address' => 'nullable|string',
            'support_hours' => 'nullable|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'company_slogan' => 'nullable|string|max:255',
            'license_number' => 'nullable|string|max:255',
            'supreme_court_disclaimer' => 'nullable|string',
            'google_map_embed' => 'nullable|string',
            'terms_and_conditions' => 'nullable|string',
            'privacy_policy' => 'nullable|string',
            'shipping_policy' => 'nullable|string',
        ]);

        $settings = CrackersSetting::getSettings();
        $settings->gst_percentage = $validated['gst_percentage'];
        $settings->min_retail_order_amount = $request->input('min_retail_order_amount', 0);
        $settings->min_wholesale_order_amount = $request->input('min_wholesale_order_amount', 0);
        $settings->enable_cod = $request->has('enable_cod');
        $settings->enable_upi = $request->has('enable_upi');
        $settings->upi_id = $validated['upi_id'] ?? null;
        $settings->enable_bank_transfer = $request->has('enable_bank_transfer');

        // Support & Contact Details
        $settings->company_name = $validated['company_name'] ?? 'S.R. TRADERS';
        $settings->support_phone = $validated['support_phone'] ?? null;
        $settings->support_email = $validated['support_email'] ?? null;
        $settings->support_address = $validated['support_address'] ?? null;
        $settings->support_hours = $validated['support_hours'] ?? null;
        $settings->company_slogan = $validated['company_slogan'] ?? null;
        $settings->license_number = $validated['license_number'] ?? null;
        $settings->supreme_court_disclaimer = $validated['supreme_court_disclaimer'] ?? null;
        $settings->google_map_embed = $validated['google_map_embed'] ?? null;

        // Legal Policies
        $settings->terms_and_conditions = $validated['terms_and_conditions'] ?? null;
        $settings->privacy_policy = $validated['privacy_policy'] ?? null;
        $settings->shipping_policy = $validated['shipping_policy'] ?? null;

        if ($request->hasFile('upi_qr_code')) {
            $path = $request->file('upi_qr_code')->store('qr_codes', 'public');
            $settings->upi_qr_code = Storage::url($path);
        }

        $settings->save();

        return redirect()->back()->with('success', 'Store, Contact, Payment & Policy Settings updated successfully!');
    }

    // MULTIPLE BANK ACCOUNTS MANAGEMENT
    public function storeBank(Request $request)
    {
        $validated = $request->validate([
            'bank_name' => 'required|string|max:255',
            'account_holder' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'ifsc_code' => 'required|string|max:255',
            'branch_name' => 'nullable|string|max:255',
        ]);

        CrackersBankAccount::create([
            'bank_name' => $validated['bank_name'],
            'account_holder' => $validated['account_holder'],
            'account_number' => $validated['account_number'],
            'ifsc_code' => $validated['ifsc_code'],
            'branch_name' => $validated['branch_name'] ?? null,
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', 'New Bank Account added successfully!');
    }

    public function updateBank(Request $request, $id)
    {
        $bank = CrackersBankAccount::findOrFail($id);

        $validated = $request->validate([
            'bank_name' => 'required|string|max:255',
            'account_holder' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'ifsc_code' => 'required|string|max:255',
            'branch_name' => 'nullable|string|max:255',
        ]);

        $bank->update([
            'bank_name' => $validated['bank_name'],
            'account_holder' => $validated['account_holder'],
            'account_number' => $validated['account_number'],
            'ifsc_code' => $validated['ifsc_code'],
            'branch_name' => $validated['branch_name'] ?? null,
        ]);

        return redirect()->back()->with('success', 'Bank Account updated successfully!');
    }

    public function toggleBankStatus($id)
    {
        $bank = CrackersBankAccount::findOrFail($id);
        $bank->is_active = !$bank->is_active;
        $bank->save();

        return redirect()->back()->with('success', 'Bank status updated!');
    }

    public function destroyBank($id)
    {
        $bank = CrackersBankAccount::findOrFail($id);
        $bank->delete();

        return redirect()->back()->with('success', 'Bank Account deleted!');
    }
}
