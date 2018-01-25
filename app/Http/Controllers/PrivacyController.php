<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class PrivacyController extends Controller
{
    public function getPrivacy()
    {
        return view('privacy');
    }
}
