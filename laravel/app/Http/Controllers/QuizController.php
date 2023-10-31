<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Quiz_access;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function index()
    {
        $quizzes = Quiz::with('user')
            ->withCount('quiz_access')
            ->withCount('quiz_user')
            ->where('user_id', Auth::user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('quiz.index', compact('quizzes'));
    }

    public function create(Request $request)
    {
        return view('quiz.create');
    }

    public function import()
    {
        return view('quiz.import');
    }

    public function importProccess(Request $request)
    {
        $this->validate($request, [
            'gptJson' => 'bail|required|json'
        ]);

        $gptJson = json_decode($request->gptJson);

        if (count($gptJson->questions) !== 10) {
            return redirect()
                ->route('quizzes.index')
                ->with('error', 'JSON inválido!');
        }

        $data = [
            'title' => 'Meu Quiz usando chatGPT',
            'slug' => 'meu-quiz-usando-chatgpt',
            'description' => 'Descrição do meu quiz usando chatGPT',
            'is_public' => true
        ];

        foreach ($gptJson->questions as $k => $question) {
            $data['question-' . ($k + 1)] = htmlspecialchars_decode($question->question);
            foreach ($question->answers as $j => $answer) {
                $data['answers-' . ($k + 1) . '-' . ($j + 1)] = htmlspecialchars_decode($answer->text);
            }
        }

        $request
            ->session()
            ->flash('gptFields', $data);

        return redirect()
            ->route('quizzes.create');
    }

    public function store(Request $request)
    {
        $this->validateFields($request);

        $quiz = new Quiz();
        $quiz->user_id = Auth::user()->id;
        $quiz->title = $request->title;
        $quiz->slug = $request->slug;
        $quiz->description = $request->description;
        $quiz->is_public = isset($request->is_public);
        $quiz->date_start = $this->parse_period_date($request->period);
        $quiz->date_end = $this->parse_period_date($request->period, 1);
        $quiz->questions = $this->format_questions($request->all());
        $quiz->save();

        return redirect()
            ->route('quizzes.index')
            ->with('success', 'O Quiz foi criado com sucesso!');
    }

    public function edit($quizID)
    {
        $quiz = Quiz::where('id', $quizID)
            ->where('user_id', Auth::user()->id)
            ->firstOrFail();

        return view('quiz.edit', compact('quiz'));
    }

    public function update($quizID, Request $request)
    {
        $quiz = Quiz::where('id', $quizID)
        ->where('user_id', Auth::user()->id)
        ->firstOrFail();

        $this->validateFields($request, $quiz->id);

        $quiz->title = $request->title;
        $quiz->slug = $request->slug;
        $quiz->description = $request->description;
        $quiz->is_public = isset($request->is_public);
        $quiz->date_start = $this->parse_period_date($request->period);
        $quiz->date_end = $this->parse_period_date($request->period, 1);
        $quiz->questions = $this->format_questions($request->all());
        $quiz->save();

        return redirect()
            ->route('quizzes.index')
            ->with('success', 'O Quiz foi editado com sucesso!');
    }

    public function delete($quizID)
    {
        $quiz = Quiz::where('id', $quizID)
            ->where('user_id', Auth::user()->id)
            ->firstOrFail();

        return view('quiz.delete', compact('quiz'));
    }

    public function destroy($quizID, Request $request)
    {
        $quiz = Quiz::where('id', $quizID)
            ->where('user_id', Auth::user()->id)
            ->firstOrFail();

        $this->validate($request, [
            'confirm' => 'bail|required',
            'password' => 'bail|required|current_password'
        ]);

        $quiz->delete();

        return redirect()
            ->route('quizzes.index')
            ->with('success', 'O Quiz foi excluído com sucesso!');
    }

    public function report($quizID)
    {
        $quiz = Quiz::where('id', $quizID)
            ->where('user_id', Auth::user()->id)
            ->firstOrFail();

        return view('quiz.report', compact('quiz'));
    }

    public function share($username, $quizSlug)
    {
        $user = User::where('username', $username)->firstOrFail();
        $quiz = Quiz::where('slug', $quizSlug)
            ->where('user_id', $user->id)
            ->where('date_start', '<=', date('Y-m-d H:i:s'))
            ->where('date_end', '>=', date('Y-m-d H:i:s'))
            ->firstOrFail();

        $questions = collect($quiz->questions)->shuffle()->map(function ($item) {
            return [
                'pergunta' => $item['question'],
                'correta' => $item['answers'][0],
                'tema' => 'Geral',
                'opcoes' => collect($item['answers'])->shuffle()->map(function ($item) {
                    return $item;
                })->toArray()
            ];
        })->toJson();

        $this->saveVisit($quiz->id);

        return view('quiz.share', compact('quiz', 'questions'));
    }

    private function saveVisit($quizID)
    {
        $quizAccess = new Quiz_Access();
        $quizAccess->quiz_id = $quizID;
        $quizAccess->referrer = $_SERVER['HTTP_REFERER'] ?? null;
        $quizAccess->user_agent = $_SERVER['HTTP_USER_AGENT'] ?? null;
        $quizAccess->ip = $_SERVER['REMOTE_ADDR'] ?? null;
        $quizAccess->save();
    }

    private function format_questions($questionsRequest)
    {
        $questions = [];

        for ($i = 1;$i <= 10;$i++) {
            $questions[$i - 1]['question'] = $questionsRequest['question-' . $i];

            for ($j = 1;$j <= 4;$j++) {
                $questions[$i - 1]['answers'][$j - 1] = $questionsRequest['answers-' . $i . '-' . $j];
            }
        }

        return $questions;
    }

    private function parse_period_date($date, $i = 0)
    {
        $date = explode('-', $date);

        return \DateTime::createFromFormat('d/m/Y H:i', trim($date[$i]))
            ->format('Y-m-d H:i:s');
    }

    private function validateFields($fields, $editId = false)
    {
        $slugRule = $editId ? 'bail|required|min:5|max:50|unique:quizzes,slug,' . $editId : 'bail|required|min:5|max:50|unique:quizzes';

        $rules = [
            'title' => 'bail|required|min:5|max:100',
            'slug' => $slugRule,
            'description' => 'bail|required',
            'period' => 'bail|required'
        ];

        for($i = 1;$i <= 10;$i++) {
            $rules['question-' . $i] = 'bail|required|min:1|max:100';
            for($j = 1;$j <= 4;$j++) {
                $rules['answers-'.$i.'-'.$j] = 'bail|required|min:1|max:100';
            }
        }

        $this->validate($fields, $rules);
    }

}
