<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Quiz_user;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Quiz;

class QuizController extends Controller
{

    public function list()
    {
        $quizzes = Quiz::select(['id', 'title', 'slug', 'description', 'date_start', 'date_end'])
            ->where('is_public', true)
            ->where('date_start', '<=', now()->format('Y-m-d H:i:s'))
            ->where('date_end', '>=', now()->format('Y-m-d H:i:s'))
            ->orderBy('date_start', 'desc')
            ->limit(5)
            ->get();

        return response()->json($quizzes);
    }

    public function find($slug)
    {
        $quiz = Quiz::with(['user:id,username,name,email'])->where('slug', $slug)->first();

        if (!$quiz) {
            return response()->json(['error' => 'Quiz não encontrado!'], 404);
        }
        if ($quiz->date_start > now()){
            return response()->json(['error' => 'Quiz ainda não começou!'], 404);
        }
        if ($quiz->date_end <= now()){
            return response()->json(['error' => 'Quiz já terminou!'], 404);
        }
        return response()->json($quiz);
    }

    public function user($username)
    {
        $user = User::select(['id', 'name', 'username', 'email'])->where('username', $username)->first();
        if (!$user) {
            return response()->json(['error' => 'Usuário não encontrado!'], 404);
        }
        $quizzes = Quiz::select(['id', 'title', 'slug', 'description', 'date_start', 'date_end'])
            ->where('user_id', $user->id)
            ->where('is_public', true)
            ->where('date_start', '<=', now()->format('Y-m-d H:i:s'))
            ->where('date_end', '>=', now()->format('Y-m-d H:i:s'))
            ->orderBy('date_start', 'desc')
            ->get();

        return response()->json([
            'user' => $user,
            'quizzes' => $quizzes
        ]);
    }

    public function show($username, $slug)
    {
        $user = User::where('username', $username)->first();
        if (!$user) {
            return response()->json(['error' => 'Usuário não encontrado!'], 404);
        }
        $quiz = Quiz::with(['user:id,username,name,email'])
            ->where('user_id', $user->id)
            ->where('slug', $slug)
            ->first();

        if (!$quiz) {
            return response()->json(['error' => 'Quiz não encontrado!'], 404);
        }
        if ($quiz->date_start > now()){
            return response()->json(['error' => 'Quiz ainda não começou!'], 404);
        }
        if ($quiz->date_end <= now()){
            return response()->json(['error' => 'Quiz já terminou!'], 404);
        }

        return response()->json($quiz);
    }

    public function store(Request $request, $username, $slug)
    {
        $user = User::where('username', $username)->first();
        if (!$user) {
            return response()->json(['error' => 'Usuário não encontrado!'], 404);
        }
        $quiz = Quiz::where('user_id', $user->id)
            ->where('slug', $slug)
            ->first();

        if (!$quiz) {
            return response()->json(['error' => 'Quiz não encontrado!'], 404);
        }
        if ($quiz->date_start > now()){
            return response()->json(['error' => 'Quiz ainda não começou!'], 404);
        }
        if ($quiz->date_end <= now()){
            return response()->json(['error' => 'Quiz já terminou!'], 404);
        }

        $quiz = Quiz::where('id', $quizID)
            ->where('date_start', '<=', date('Y-m-d H:i:s'))
            ->where('date_end', '>=', date('Y-m-d H:i:s'))
            ->firstOrFail();

        $appData = new Quiz_user();
        $appData->quiz_id = $quiz->id;
        $appData->name = $request->name ?? 'anonymous';
        $appData->time_start = Carbon::createFromTimestampMs($request->time_start);
        $appData->time_end = Carbon::createFromTimestampMs($request->time_end);
        $appData->time_left = $request->time_left;
        $appData->life_left = $request->life_left;
        $appData->score = $request->score;
        $appData->end_type = $request->end_type;
        $appData->overlay_views = $request->overlay_views;
        $appData->questions = $request->questions;
        $appData->client = $request->client;
        //$appData->save();

        return response()->json(['success' => true]);
    }

    public function score($username, $slug)
    {
        $user = User::where('username', $username)->first();

        if (!$user) {
            return response()->json(['error' => 'Usuário não encontrado!'], 404);
        }

        $quiz = Quiz::where('user_id', $user->id)
            ->where('slug', $slug)
            ->first();

        if (!$quiz) {
            return response()->json(['error' => 'Quiz não encontrado!'], 404);
        }

        $users = Quiz_user::select(['name', 'score'])
            ->where('quiz_id', $quiz->id)
            ->whereNotNull('score')
            ->orderBy('score', 'desc')
            ->orderBy('created_at', 'asc')
            ->limit(10)
            ->get();

        return response()->json($users);
    }

}
