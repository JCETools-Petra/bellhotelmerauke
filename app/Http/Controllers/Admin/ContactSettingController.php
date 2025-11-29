<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ContactSettingController extends Controller
{
    public function edit()
    {
        $settings = ContactSetting::pluck('value', 'key')->all();
        return view('admin.contact.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'contact_address' => 'nullable|string',
            'contact_phone' => 'nullable|string|max:25',
            'contact_email' => 'nullable|email|max:255',
            'contact_maps_embed' => 'nullable|string',
            'contact_seo_title' => 'nullable|string|max:255',
            'contact_seo_description' => 'nullable|string|max:160',
        ]);

        $settings = $request->except(['_token', '_method']);

        foreach ($settings as $key => $value) {
            ContactSetting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        Cache::forget('contact_settings'); // Hapus cache agar frontend update

        return redirect()->back()->with('success', 'Contact settings updated successfully.');
    }
}