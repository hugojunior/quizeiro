<?php

namespace App\Http\Controllers;

use App\Models\Quiz;

class HomeController extends Controller
{
    public function index()
    {
        return redirect()
            ->route('quizzes.index');
    }

    public function public()
    {
        $publicQuizzes = Quiz::where('is_public', true)
            ->where('date_start', '<=', date('Y-m-d H:i:s'))
            ->where('date_end', '>=', date('Y-m-d H:i:s'))
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        return view('home-public', compact('publicQuizzes'));
    }
}
