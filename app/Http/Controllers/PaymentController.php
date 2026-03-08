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
        
        $settings = PaymentProvider::all(); // Wait, this is providers. Settings should be from Settings model.
        // Let's fix this in the next tool call properly.
        $allSettings = \App\Models\Setting::all()->pluck('value', 'key');

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
}
