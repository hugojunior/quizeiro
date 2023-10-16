<?php

namespace App\Http\Controllers;

class QuizController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
}
