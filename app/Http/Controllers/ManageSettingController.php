<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ManageSettingController extends Controller
{
    public function index()
    {
        $settingsData = [
            'username' => 'admin_user',
            'email' => 'admin@example.com',
            'email_notifications' => true, 
            'push_notifications' => false,
        ];
        
        return view('manage-setting.index', compact('settingsData'));
    }

    public function update(Request $request)
    {
        return redirect()->route('settings.index')->with('warning', 'Mode demo aktif! Perubahan tidak disimpan.');
    }
}