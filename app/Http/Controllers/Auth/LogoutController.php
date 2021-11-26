<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    public function logout(Request $request)
    {
        $deleted = $request->user()->currentAccessToken()->delete();
        return $deleted == '1'
            ? response()->json([
            'message' => 'done'
        ]) : $deleted;
    }

}
