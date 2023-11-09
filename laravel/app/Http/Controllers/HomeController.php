<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use Illuminate\Http\Request;
use Mail;

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

    public function sendContact(Request $request)
    {
        $this->validate($request, [
            'name' => 'bail|required|min:3|max:30',
            'email' => 'bail|required|email|min:5|max:30',
            'message' => 'bail|required|min:3',
        ]);

        Mail::send('mail.contact', [
            'name' => $request->name,
            'email' => $request->email,
            'text' => $request->message,
        ], function ($message) use ($request) {
            $message->to('contato@hugojunior.com', 'Hugo Júnior')
                ->subject('Mensagem via Quizeiro')
                ->from($request->email, $request->name);
        });

        return redirect()
            ->route('site')
            ->withFragment('contato')
            ->with('success', 'Sua mensagem foi enviada com sucesso!');
    }

}
