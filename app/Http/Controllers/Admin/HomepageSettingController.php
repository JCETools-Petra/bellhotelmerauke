<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomepageSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class HomepageSettingController extends Controller
{
    public function edit()
    {
        $settings = HomepageSetting::pluck('value', 'key')->all();
        return view('admin.homepage.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'website_title' => 'nullable|string|max:255',
            'logo_path' => 'nullable|image|max:2048',
            'favicon_path' => 'nullable|image|mimes:jpeg,png,ico,svg|max:2048',
            'logo_height' => 'nullable|numeric|min:20|max:100',
            'show_logo_text' => 'nullable|string',
            'featured_display_option' => 'nullable|array',
            'show_about_section' => 'nullable|string',
            'hero_bg_image' => 'nullable|image|max:2048',
            'hero_title' => 'nullable|string|max:255',
            'hero_subtitle' => 'nullable|string',
            'about_title' => 'nullable|string|max:255',
            'about_content' => 'nullable|string',
            'hero_text_align' => 'nullable|string',
            'hero_title_font_size' => 'nullable|numeric',
            'hero_title_font_family' => 'nullable|string',
            'hero_subtitle_font_size' => 'nullable|numeric',
            'hero_subtitle_font_family' => 'nullable|string',
            'about_text_align' => 'nullable|string',
            'about_title_font_size' => 'nullable|numeric',
            'about_title_font_family' => 'nullable|string',
            'about_content_font_size' => 'nullable|numeric',
            'about_content_font_family' => 'nullable|string',
            'layout_icon_classroom' => 'nullable|image|max:2048',
            'layout_icon_theatre' => 'nullable|image|max:2048',
            'layout_icon_ushape' => 'nullable|image|max:2048',
            'layout_icon_round' => 'nullable|image|max:2048',
            'layout_icon_board' => 'nullable|image|max:2048',
            'hero_slider_height' => 'nullable|string|max:50',
            'hero_slider_width' => 'nullable|string|max:50',
        ]);

        $textInputs = $request->except([
            '_token', '_method', 'logo_path', 'favicon_path', 'hero_bg_image',
            'layout_icon_classroom', 'layout_icon_theatre', 'layout_icon_ushape',
            'layout_icon_round', 'layout_icon_board', 'featured_display_option',
            'show_about_section', 'show_logo_text'
        ]);

        foreach ($textInputs as $key => $value) {
            if ($value !== null) {
                HomepageSetting::updateOrCreate(['key' => $key], ['value' => $value]);
            }
        }

        HomepageSetting::updateOrCreate(
            ['key' => 'show_about_section'],
            ['value' => $request->has('show_about_section') ? '1' : '0']
        );

        HomepageSetting::updateOrCreate(
            ['key' => 'show_logo_text'],
            ['value' => $request->has('show_logo_text') ? '1' : '0']
        );

        $featuredOptions = $request->input('featured_display_option', []);
        HomepageSetting::updateOrCreate(
            ['key' => 'featured_display_option'],
            ['value' => implode(',', $featuredOptions)]
        );

        $fileKeys = ['logo_path', 'favicon_path', 'hero_bg_image', 'layout_icon_classroom', 'layout_icon_theatre', 'layout_icon_ushape', 'layout_icon_round', 'layout_icon_board'];
        foreach ($fileKeys as $key) {
            if ($request->hasFile($key)) {
                $oldPath = HomepageSetting::where('key', $key)->value('value');
                if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
                $path = $request->file($key)->store('settings', 'public');
                HomepageSetting::updateOrCreate(['key' => $key], ['value' => $path]);
            }
        }

        Cache::forget('homepage_settings');

        return redirect()->back()->with('success', 'Homepage settings updated successfully.');
    }
}