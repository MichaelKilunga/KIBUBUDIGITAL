<?php

namespace App\Http\Controllers;

use App\Models\PaymentIntent;
use App\Models\PaymentProvider;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $mobileProviders = PaymentProvider::where('type', 'mobile_money')->get();
        $bankProviders = PaymentProvider::where('type', 'bank')->get();
        $allSettings = \App\Models\Setting::all()->pluck('value', 'key');
        
        if (isset($allSettings['site_name'])) {
            config(['app.name' => $allSettings['site_name']]);
        }
        
        return view('welcome', compact('mobileProviders', 'bankProviders', 'allSettings'));
    }

    public function logIntent(Request $request)
    {
        $request->validate([
            'provider_name' => 'required|string',
            'device_type' => 'required|string',
        ]);

        PaymentIntent::create([
            'provider_name' => $request->provider_name,
            'device_type' => $request->device_type,
            'ip_address' => $request->ip(),
        ]);

        return response()->json(['status' => 'success']);
    }

    public function admin()
    {
        $intents = PaymentIntent::latest()->paginate(20);
        $totalIntents = PaymentIntent::count();
        $providerStats = PaymentIntent::select('provider_name', \DB::raw('count(*) as total'))
            ->groupBy('provider_name')
            ->get();
        
        $allSettings = \App\Models\Setting::all()->pluck('value', 'key');
        if (isset($allSettings['site_name'])) {
            config(['app.name' => $allSettings['site_name']]);
        }

        return view('admin.dashboard', compact('intents', 'totalIntents', 'providerStats', 'allSettings'));
    }

    public function saveSettings(Request $request)
    {
        $data = $request->except('_token');
        
        foreach ($data as $key => $value) {
            \App\Models\Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return back()->with('success', __('messages.success_settings'));
    }

    public function storeProvider(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'type' => 'required|in:mobile_money,bank',
            'account_number' => 'required|string',
        ]);

        PaymentProvider::create($request->all());
        return back()->with('success', 'Provider added successfully!');
    }

    public function updateProvider(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'type' => 'required|in:mobile_money,bank',
            'account_number' => 'required|string',
        ]);

        $provider = PaymentProvider::findOrFail($id);
        $provider->update($request->all());
        return back()->with('success', 'Provider updated successfully!');
    }

    public function deleteProvider($id)
    {
        $provider = PaymentProvider::findOrFail($id);
        $provider->delete();
        return back()->with('success', 'Provider deleted successfully!');
    }
}
