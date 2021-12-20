<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function upload(Request $request)
    {
        // validate file uploaded
        $request->validate([
            'image' => 'required|image:jpeg,png,jpg,gif,svg'
        ]);

        $image_path = $request->file('image')->store('public');
        return response([
            'image_name' => pathinfo($image_path)['basename']
        ]);
    }
}
