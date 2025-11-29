<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    /**
     * Menampilkan halaman formulir pengaturan.
     */
    public function index()
    {
        // Ambil semua pengaturan dalam bentuk [key => value]
        $settings = Setting::pluck('value', 'key')->all();

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Menyimpan atau memperbarui semua pengaturan.
     */
    public function update(Request $request)
    {
        // 1) Validasi SEMUA input yang BUKAN checkbox
        $validatedData = $request->validate([
            // General
            'website_title' => 'required|string|max:255',
            'logo_height' => 'required|integer|min:20|max:100',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
            'favicon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,ico|max:2048',

            // Hero Section
            'hero_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
            'hero_title' => 'required|string|max:255',
            'hero_subtitle' => 'required|string',

            // Contact
            'contact_address' => 'nullable|string',
            'contact_phone' => 'nullable|string',
            'contact_email' => 'nullable|email',
            'contact_facebook' => 'nullable|url',
            'contact_instagram' => 'nullable|url',
            'contact_linkedin' => 'nullable|url',
            'contact_youtube' => 'nullable|url',
            'contact_tiktok' => 'nullable|url',
            'contact_maps_embed' => 'nullable|string',

            // Legal
            'terms_and_conditions' => 'nullable|string',

            // Midtrans (non-checkbox)
            'midtrans_merchant_id' => 'sometimes|required|string|max:255',
            'midtrans_client_key' => 'sometimes|required|string|max:255',
            'midtrans_server_key' => 'sometimes|required|string|max:255',

            // WhatsApp Templates
            'whatsapp_customer_message' => 'required|string',
            'whatsapp_admin_message' => 'required|string',

            // Booking Method
            'booking_method' => 'required|in:direct,manual',
            'running_text_content' => 'nullable|string|max:255',
            'running_text_url' => 'nullable|url|max:255',
            
            // Validasi Ikon Layout MICE
            'layout_icon_classroom' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'layout_icon_theatre'   => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'layout_icon_ushape'    => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'layout_icon_round'     => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'layout_icon_board'     => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

            // --- PERBAIKAN: VALIDASI UNTUK NOTIFIKASI PAY AT HOTEL DITAMBAHKAN DI SINI ---
            'fonnte_api_key' => 'nullable|string', // Jika Anda menambahkan field ini
            'whatsapp_admin_receiver' => 'nullable|string',
            'whatsapp_supervisor_receivers' => 'nullable|string',
            'whatsapp_pay_at_hotel_admin_template' => 'nullable|string',
            'whatsapp_pay_at_hotel_customer_template' => 'nullable|string',
            // ----------------------------------------------------------------------------

            // MICE Commission Rate
            'mice_commission_rate' => 'required|numeric|min:0|max:100',
        ]);

        // 2) Proses file upload (logo, favicon, hero image)
        $filesToUpload = ['logo', 'favicon', 'hero_image'];
        foreach ($filesToUpload as $fileKey) {
            if ($request->hasFile($fileKey)) {
                $oldPath = Setting::where('key', $fileKey . '_path')->value('value');
                if ($oldPath) {
                    Storage::disk('public')->delete($oldPath);
                }
                $path = $request->file($fileKey)->store('settings', 'public');
                $validatedData[$fileKey . '_path'] = $path;
            }
        }
        
        // Proses Upload Ikon Layout MICE
        $layout_icons = [
            'layout_icon_classroom',
            'layout_icon_theatre',
            'layout_icon_ushape',
            'layout_icon_round',
            'layout_icon_board',
        ];

        foreach ($layout_icons as $key) {
            if ($request->hasFile($key)) {
                $oldPath = Setting::where('key', $key)->value('value');
                if ($oldPath) {
                    Storage::disk('public')->delete($oldPath);
                }
                $path = $request->file($key)->store('settings', 'public');
                Setting::updateOrCreate(['key' => $key], ['value' => $path]);
            }
        }

        // Singkirkan field file supaya tidak ikut disimpan sebagai string biasa
        unset($validatedData['logo'], $validatedData['favicon'], $validatedData['hero_image']);

        // 3) Simpan semua data non-checkbox yang sudah divalidasi
        foreach ($validatedData as $key => $value) {
            if (in_array($key, $layout_icons)) {
                continue;
            }
            
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value ?? '']
            );
        }

        // 4) Simpan semua CHECKBOX
        $checkboxes = [
            'show_logo_text',
            'midtrans_is_production',
            'running_text_enabled',
        ];

        foreach ($checkboxes as $key) {
            $value = $request->boolean($key) ? '1' : '0';
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        // 5) Hapus cache & redirect
        Cache::forget('site_settings');

        return redirect()
            ->route('admin.settings.index')
            ->with('success', 'Settings updated successfully.');
    }
}