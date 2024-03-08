<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Expense;
use App\Models\OrderCharge;
use App\Models\OrderDetail;
use App\Models\OrderMaster;
use App\Models\OrderSessionData;
use App\Models\OrderTax;
use App\Models\OrderTerm;
use App\Models\Product;
use App\Models\Receipt;
use App\Models\Setting;
use App\Services\SettingsService;
use App\Traits\FileUploadTrait;
use Cache;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    use FileUploadTrait;
    function index(): View
    {
        return view('admin.setting.index');
    }
    function UpdateGeneralSetting(Request $request)
    {
        $validatedData = $request->validate([
            'site_name' => ['required', 'max:255'],
            'site_prefix' => ['required', 'min:3','max:4'],
            'site_email' => ['nullable', 'max:255'],
            'site_phone' => ['nullable', 'max:255'],
            'site_default_currency' => ['required', 'max:4'],
            'site_currency_icon' => ['required', 'max:4'],
            'site_currency_icon_position' => ['required', 'max:255'],
            'site_inclusive_tax' => ['sometimes', 'boolean'],
        ]);
        if (!$request->site_inclusive_tax) {
            Setting::updateOrCreate(
                ['key' => 'site_inclusive_tax'],
                ['value' => 0]
            );
        }
        foreach ($validatedData as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
        $settingsService = app(SettingsService::class);
        $settingsService->clearCachedSettings();
        toastr()->success('Updated Successfully!');
        return redirect()->back();
    }
    function UpdateLogoSetting(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'logo' => ['nullable', 'image', 'max:1000'],
            'footer_logo' => ['nullable', 'image', 'max:1000'],
            'favicon' => ['nullable', 'image', 'max:1000'],
            'breadcrumb' => ['nullable', 'image', 'max:1000'],
        ]);
        foreach ($validatedData as $key => $value) {
            $imagePatch = $this->uploadImage($request, $key, '', '/uploads/logos');
            if (!empty($imagePatch)) {
                $oldPath = config('settings.' . $key);
                $this->removeImage($oldPath);
                Setting::updateOrCreate(
                    ['key' => $key],
                    ['value' => $imagePatch]
                );
            }
        }
        $settingsService = app(SettingsService::class);
        $settingsService->clearCachedSettings();
        Cache::forget('mail_settings');
        toastr()->success('Updated Successfully!');
        return redirect()->back();
    }
    function DeleteData(Request $request)
    {
        $data = $request->modules;
        foreach ($data as $key => $value) {
            if ($value == 'clients')
            {
                Client::query()->delete();
            }
            if ($value == 'products')
            {
                Product::query()->delete();
            }
            if ($value == 'expenses')
            {
                Expense::query()->delete();
            }
            if ($value == 'orders')
            {
                $orders = Receipt::where('transaction_type', 'order')->get();
                foreach ($orders as $order) {
                    $order_id = $order->transaction_reference;
                    OrderCharge::where('order_charge_master_id', $order_id)->delete();
                    OrderTerm::where('order_terms_master_id', $order_id)->delete();
                    OrderTax::where('order_tax_master_id', $order_id)->delete();
                    OrderDetail::where('order_detail_master_id', $order_id)->delete();
                    OrderSessionData::where('order_session_master_id', $order_id)->delete();
                    OrderMaster::where('id', $order_id)->delete();
                    Receipt::where('transaction_reference', $order_id)->delete();
                }
            }
            if ($value == 'receipts')
            {
                Receipt::whereNotNull('received_amount')->delete();
            }
        }
        return response(['status' => "success", 'message' => 'Data deleted successfully.']);
    }
}
