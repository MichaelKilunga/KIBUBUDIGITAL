<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class ManifestController extends Controller
{
    public function index()
    {
        $allSettings = Setting::all()->pluck('value', 'key');
        
        $logoPath = isset($allSettings['site_logo']) ? $allSettings['site_logo'] : 'images/logo.png';
        $fullPath = public_path($logoPath);
        $mimeType = 'image/png'; // Default
        
        if (file_exists($fullPath)) {
            $ext = pathinfo($fullPath, PATHINFO_EXTENSION);
            if (in_array(strtolower($ext), ['jpg', 'jpeg'])) {
                $mimeType = 'image/jpeg';
            } elseif (strtolower($ext) === 'webp') {
                $mimeType = 'image/webp';
            } elseif (strtolower($ext) === 'svg') {
                $mimeType = 'image/svg+xml';
            }
        }
        
        $manifest = [
            "name" => $allSettings['site_name'] ?? "Kibubu Digital",
            "short_name" => $allSettings['site_name'] ?? "Kibubu",
            "description" => $allSettings['hero_lead_en'] ?? "Charity payment routing landing page.",
            "start_url" => "/",
            "display" => "standalone",
            "background_color" => "#ffffff",
            "theme_color" => $allSettings['primary_color'] ?? "#D4AF37",
            "icons" => [
                [
                    "src" => asset($logoPath),
                    "sizes" => "192x192",
                    "type" => $mimeType,
                    "purpose" => "any maskable"
                ],
                [
                    "src" => asset($logoPath),
                    "sizes" => "512x512",
                    "type" => $mimeType,
                    "purpose" => "any maskable"
                ]
            ]
        ];

        return response()->json($manifest);
    }
}
