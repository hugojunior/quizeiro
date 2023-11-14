<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Quiz_access;
use App\Models\Quiz_user;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use WhichBrowser\Parser;

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

        if (!isset($gptJson->title)) {
            return redirect()
                ->route('quizzes.import')
                ->with('error', 'Não foi possível localizar o Título do Quiz (title) no seu JSON!');
        }

        if (!isset($gptJson->slug)) {
            return redirect()
                ->route('quizzes.import')
                ->with('error', 'Não foi possível localizar o Identificador do Quiz (slug) no seu JSON!');
        }

        if (!isset($gptJson->summary)) {
            return redirect()
                ->route('quizzes.import')
                ->with('error', 'Não foi possível localizar a Descrição do Quiz (summary) no seu JSON!');
        }

        foreach($gptJson->questions as $question) {
            if (strlen($question->question) > 100) {
                return redirect()
                    ->route('quizzes.import')
                    ->with([
                        'error' => 'A pergunta não pode ter mais de 100 caracteres: ' . $question->question,
                        'gptJson' => $request->gptJson
                    ]);
            }

            foreach($question->answers as $answer) {
                if (strlen($answer) > 100) {
                    return redirect()
                        ->route('quizzes.import')
                        ->with([
                            'error' => 'A resposta não pode ter mais de 100 caracteres: ' . $answer,
                            'gptJson' => $request->gptJson
                        ]);
                }
            }
        }

        if (count($gptJson->questions) !== 10) {
            return redirect()
                ->route('quizzes.import')
                ->with('error', 'Não foi possível identificar as 10 perguntas no seu JSON!');
        }

        $data = [
            'title' => $gptJson->title,
            'slug' => $gptJson->slug,
            'description' => $gptJson->summary,
            'is_public' => true
        ];

        foreach ($gptJson->questions as $k => $question) {
            $data['question-' . ($k + 1)] = htmlspecialchars_decode($question->question);
            foreach ($question->answers as $j => $answer) {
                $data['answers-' . ($k + 1) . '-' . ($j + 1)] = htmlspecialchars_decode($answer);
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

        $browsers = Quiz_access::select('user_agent')
            ->where('quiz_id', $quizID)
            ->groupBy('user_agent')
            ->get()
            ->pluck('user_agent')
            ->countBy(function ($userAgent) {
                $device = new Parser($userAgent);
                return $device->browser->getName() ?? '';
            })->filter(function ($value, $key) {
                return $key !== '';
            })
            ->slice(0, 6)
            ->sortDesc();

        $os = Quiz_access::select('user_agent')
            ->where('quiz_id', $quizID)
            ->groupBy('user_agent')
            ->get()
            ->pluck('user_agent')
            ->countBy(function ($userAgent) {
                $device = new Parser($userAgent);
                return $device->os->getName() ?? '';
            })
            ->filter(function ($value, $key) {
                return $key !== '';
            })
            ->slice(0, 6)
            ->sortDesc();

        $devices = Quiz_access::select('user_agent')
            ->where('quiz_id', $quizID)
            ->groupBy('user_agent')
            ->get()
            ->pluck('user_agent')
            ->countBy(function ($userAgent) {
                $device = new Parser($userAgent);
                return $device->device->type ?? '';
            })
            ->filter(function ($value, $key) {
                return $key !== '';
            })
            ->slice(0, 6)
            ->sortDesc();

        $visits = Quiz_access::select('created_at')->where('quiz_id', $quiz->id)->get();
        $totalVisits = $visits->count();
        $totalUniqueVisits = Quiz_access::select('ip', 'user_agent')->where('quiz_id', $quiz->id)->groupBy('ip', 'user_agent')->get()->count();
        $refererAccess = Quiz_access::select('referrer', DB::raw('COUNT(*) as total'))->where('quiz_id', $quiz->id)->whereNotNull('referrer')->groupBy('referrer')->limit(5)->orderBy('total', 'desc')->get();
        $answers = Quiz_user::where('quiz_id', $quiz->id)->orderBy('score', 'desc')->get();
        $totalAnswers = $answers->count();
        $totalSuccessAnswers = Quiz_user::where('quiz_id', $quiz->id)->where('end_type', 'success')->count();
        $successRate = $totalAnswers > 0 ? round(($totalSuccessAnswers / $totalAnswers) * 100, 2) : 0;
        $scores = Quiz_user::select(['created_at','name', 'score'])
            ->where('quiz_id', $quiz->id)
            ->whereNotNull('score')
            ->orderBy('score', 'desc')
            ->limit(10)
            ->get();
        $lastDays = collect(array_map(fn ($day) => Carbon::now()->copy()->subDays($day), range(0, 13)))->reverse();
        $dataGraphVisits = [];
        $dataGraphAnswers = [];
        $dataGraphDates = [];
        $dataGraphHours = [
            '0-4' => 0,
            '4-8' => 0,
            '8-12' => 0,
            '12-16' => 0,
            '16-20' => 0,
            '20-24' => 0
        ];

        foreach($visits as $visit) {
            $hour = Carbon::createFromFormat('Y-m-d H:i:s', $visit->created_at)->hour;
            if ($hour >= 0 && $hour < 4) {
                $dataGraphHours['0-4']++;
            } elseif ($hour >= 4 && $hour < 8) {
                $dataGraphHours['4-8']++;
            } elseif ($hour >= 8 && $hour < 12) {
                $dataGraphHours['8-12']++;
            } elseif ($hour >= 12 && $hour < 16) {
                $dataGraphHours['12-16']++;
            } elseif ($hour >= 16 && $hour < 20) {
                $dataGraphHours['16-20']++;
            } elseif ($hour >= 20 && $hour < 24) {
                $dataGraphHours['20-24']++;
            }
        }
        $dataGraphHours = collect($dataGraphHours);

        foreach($lastDays as $day) {
            $dataGraphVisits[] = Quiz_access::where('quiz_id', $quiz->id)->whereDate('created_at', $day)->count();
            $dataGraphAnswers[] = Quiz_user::where('quiz_id', $quiz->id)->whereDate('created_at', $day)->count();
            $dataGraphDates[] = $day->format('d/m');
        }

        return view('quiz.report', compact(
            'quiz',
            'totalVisits',
            'totalUniqueVisits',
            'totalAnswers',
            'successRate',
            'scores',
            'refererAccess',
            'dataGraphVisits',
            'dataGraphAnswers',
            'dataGraphDates',
            'dataGraphHours',
            'devices',
            'browsers',
            'os',
            'answers'
        ));
    }

    public function reportAnswer($quizID, $answerID)
    {
        $quiz = Quiz::where('id', $quizID)
            ->where('user_id', Auth::user()->id)
            ->firstOrFail();

        $answer = Quiz_user::where('quiz_id', $quizID)
            ->where('id', $answerID)
            ->firstOrFail();

        $position = is_null($answer->score) ? "" : "#" . Quiz_user::where('quiz_id', $quizID)
            ->where('score', '>', $answer->score)
            ->count() + 1;

        return view('quiz.report-answer', compact('quiz', 'answer', 'position'));
    }

    public function reportAnswerDestroy($quizID, $answerID)
    {
        $quiz = Quiz::where('id', $quizID)
            ->where('user_id', Auth::user()->id)
            ->firstOrFail();

        $answer = Quiz_user::where('quiz_id', $quizID)
            ->where('id', $answerID)
            ->firstOrFail();

        $answer->delete();

        return redirect()
            ->route('quizzes.report', $quiz->id)
            ->with('success', 'A resposta foi excluída com sucesso!');
    }

    public function share($username, $quizSlug)
    {
        $user = User::where('username', $username)->firstOrFail();
        $quiz = Quiz::where('slug', $quizSlug)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $this->saveVisit($quiz->id);

        if ($quiz->date_start > date('Y-m-d H:i:s')) {
            return view('quiz.scheduled', compact('quiz'));
        } elseif($quiz->date_end < date('Y-m-d H:i:s')) {
            return view('quiz.archived', compact('quiz'));
        } else {
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

            return view('quiz.share', compact('quiz', 'questions'));
        }
    }

    public function profile($username)
    {
        $user = User::where('username', $username)->firstOrFail();
        $quizzes = Quiz::where('user_id', $user->id)
            ->where('is_public', true)
            ->where('user_id', $user->id)
            ->where('date_end', '>=', date('Y-m-d H:i:s'))
            ->orderBy('date_start', 'desc')
            ->get();

        return view('quiz.profile', compact('user', 'quizzes'));
    }

    public function score($quizID)
    {
        $quiz = Quiz::where('id', $quizID)
            ->where('date_start', '<=', date('Y-m-d H:i:s'))
            ->where('date_end', '>=', date('Y-m-d H:i:s'))
            ->firstOrFail();

        return Quiz_user::select(['name', 'score'])
            ->where('quiz_id', $quiz->id)
            ->whereNotNull('score')
            ->orderBy('score', 'desc')
            ->limit(6)
            ->get();
    }

    public function rank($username, $quizSlug, Request $request)
    {
        $user = User::where('username', $username)->firstOrFail();
        $quiz = Quiz::where('slug', $quizSlug)
            ->where('user_id', $user->id)
            ->firstOrFail();
        $quiz_users = Quiz_user::select(['name', 'score'])
            ->where('quiz_id', $quiz->id)
            ->whereNotNull('score')
            ->orderBy('score', 'desc')
            ->limit(6)
            ->get();

        return view('quiz.rank', compact('user', 'quiz', 'quiz_users'));
    }

    public function scoreStore($quizID, Request $request)
    {
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
        $appData->save();
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

        $vQuestions = [];
        for($i = 1;$i <= 10;$i++) {
            $questionKey = 'question-' . $i;
            $rules[$questionKey] = 'bail|required|min:1|max:100|not_in:' . implode(',', $vQuestions);
            $vQuestions[] = $fields->$questionKey;
            $vAnswers = [];
            for($j = 1;$j <= 4;$j++) {
                $answerKey = 'answers-' . $i . '-' . $j;
                $rules[$answerKey] = 'bail|required|min:1|max:100|not_in:' . implode(',', $vAnswers);
                $vAnswers[] = $fields->$answerKey;
            }
        }

        $this->validate($fields, $rules, [
            'not_in' => 'A pergunta ou resposta (:attribute) não pode se repetir!'
        ]);
    }

}
