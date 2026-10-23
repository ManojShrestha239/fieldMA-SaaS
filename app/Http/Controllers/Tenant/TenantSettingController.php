<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TenantSettingController extends Controller
{
    public function edit()
    {
        $tenant = tenant();
        $plan = $tenant->plan;

        return view('tenant.settings.branding', compact('tenant', 'plan'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'vat_number' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:255'],
            'theme_color' => ['nullable', 'string', 'regex:/^#[a-fA-F0-9]{6}$/'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,svg,webp', 'max:2048'],
        ]);

        $tenant = tenant();
        $data = $tenant->data ?? [];

        if ($request->filled('theme_color')) {
            $data['theme_color'] = $validated['theme_color'];
        }

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('branding', 'public');
            $data['logo_url'] = Storage::disk('public')->url($path);
        }

        $tenant->update([
            'company_name' => $validated['company_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'vat_number' => $validated['vat_number'] ?? null,
            'address' => $validated['address'] ?? null,
            'data' => $data,
        ]);

        return back()->with('Status', 'Company settings & branding updated successfully.');
    }
}
