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

        if (class_exists('\App\Models\CompanyDetail')) {
            $cd = \App\Models\CompanyDetail::firstOrCreate(['id' => 1]);
            $cd->company_name = $settings->company_name;
            if (!empty($settings->company_slogan)) {
                $cd->company_slogan = $settings->company_slogan;
            }
            if (!empty($settings->support_phone)) {
                $cleanDigits = preg_replace('/[^0-9]/', '', $settings->support_phone);
                if (strlen($cleanDigits) >= 10) {
                    $cd->company_mobile = substr($cleanDigits, -10);
                    $cd->support_mobile = substr($cleanDigits, -10);
                }
            }
            if (!empty($settings->support_email)) {
                $cd->company_email = $settings->support_email;
                $cd->support_email = $settings->support_email;
            }
            $cd->save();
        }
        if (class_exists('\App\Models\Appearance')) {
            $app = \App\Models\Appearance::where('type', 'web')->first();
            if ($app) {
                $app->title = $settings->company_name;
                $app->save();
            }
        }

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
            'upi_id' => 'nullable|string|max:255',
            'qr_code' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:4096',
        ]);

        $qrCodePath = null;
        if ($request->hasFile('qr_code')) {
            $file = $request->file('qr_code');
            $filename = 'bank_qr_' . time() . '_' . rand(100, 999) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/bank_qr'), $filename);
            $qrCodePath = 'uploads/bank_qr/' . $filename;
        }

        $isFirst = CrackersBankAccount::count() === 0;

        CrackersBankAccount::create([
            'bank_name' => $validated['bank_name'],
            'account_holder' => $validated['account_holder'],
            'account_number' => $validated['account_number'],
            'ifsc_code' => $validated['ifsc_code'],
            'branch_name' => $validated['branch_name'] ?? null,
            'upi_id' => $validated['upi_id'] ?? null,
            'qr_code' => $qrCodePath,
            'is_primary' => $isFirst || $request->has('is_primary'),
            'is_active' => true,
        ]);

        if ($request->has('is_primary')) {
            $lastCreated = CrackersBankAccount::latest('id')->first();
            if ($lastCreated) {
                CrackersBankAccount::where('id', '!=', $lastCreated->id)->update(['is_primary' => false]);
            }
        }

        if (class_exists('\App\Models\Account\BankAccount')) {
            \App\Models\Account\BankAccount::syncStoreBankAccounts();
        }

        return redirect()->back()->with('success', 'New Bank Account added successfully!');
    }

    public function setPrimaryBank($id)
    {
        $targetBank = CrackersBankAccount::findOrFail($id);

        CrackersBankAccount::query()->update(['is_primary' => false]);
        $targetBank->is_primary = true;
        $targetBank->is_active = true;
        $targetBank->save();

        if (class_exists('\App\Models\Account\BankAccount')) {
            \App\Models\Account\BankAccount::syncStoreBankAccounts();
        }

        return redirect()->back()->with('success', $targetBank->bank_name . ' set as Primary Bank Account!');
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
            'upi_id' => 'nullable|string|max:255',
            'qr_code' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:4096',
        ]);

        $qrCodePath = $bank->qr_code;
        if ($request->hasFile('qr_code')) {
            $file = $request->file('qr_code');
            $filename = 'bank_qr_' . time() . '_' . rand(100, 999) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/bank_qr'), $filename);
            $qrCodePath = 'uploads/bank_qr/' . $filename;
        }

        if ($request->has('is_primary') && $request->is_primary) {
            CrackersBankAccount::query()->update(['is_primary' => false]);
            $bank->is_primary = true;
            $bank->is_active = true;
        }

        $bank->update([
            'bank_name' => $validated['bank_name'],
            'account_holder' => $validated['account_holder'],
            'account_number' => $validated['account_number'],
            'ifsc_code' => $validated['ifsc_code'],
            'branch_name' => $validated['branch_name'] ?? null,
            'upi_id' => $validated['upi_id'] ?? null,
            'qr_code' => $qrCodePath,
        ]);

        if (class_exists('\App\Models\Account\BankAccount')) {
            \App\Models\Account\BankAccount::syncStoreBankAccounts();
        }

        return redirect()->back()->with('success', 'Bank Account updated successfully!');
    }

    public function toggleBankStatus($id)
    {
        $bank = CrackersBankAccount::findOrFail($id);
        $bank->is_active = !$bank->is_active;
        $bank->save();

        if (class_exists('\App\Models\Account\BankAccount')) {
            if ($ba = \App\Models\Account\BankAccount::where('account_number', $bank->account_number)->first()) {
                $ba->is_active = $bank->is_active;
                $ba->save();
            }
        }

        return redirect()->back()->with('success', 'Bank status updated!');
    }

    public function destroyBank($id)
    {
        $bank = CrackersBankAccount::findOrFail($id);
        if (class_exists('\App\Models\Account\BankAccount')) {
            \App\Models\Account\BankAccount::where('account_number', $bank->account_number)->delete();
        }

        $bank->delete();

        // If primary was deleted, promote another bank as primary
        if ($bank->is_primary) {
            if ($nextBank = CrackersBankAccount::first()) {
                $nextBank->is_primary = true;
                $nextBank->save();
            }
        }

        return redirect()->back()->with('success', 'Bank Account deleted!');
    }
}
