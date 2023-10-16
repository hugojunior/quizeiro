<?php

namespace App\Http\Controllers;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
}
