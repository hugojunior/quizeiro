<?php

namespace App\Http\Controllers;

use Auth;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function edit()
    {
        return view('account.edit');
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'bail|required|min:5|max:120',
            'username' => ['bail', 'required', 'min:3', 'max:30', Rule::unique('users')->ignore(Auth()->user())],
            'email' => ['bail', 'required', 'email', Rule::unique('users')->ignore(Auth()->user())]
        ]);

        Auth::user()->update([
            'name' => $request->name,
            'username' => Str::slug($request->username),
            'email' => trim(Str::lower($request->email))
        ]);

        return redirect()
            ->route('account.edit')
            ->with('success', 'Os dados da sua conta foram atualizados com sucesso!');
    }

    public function password()
    {
        return view('account.password');
    }

    public function passwordUpdate(Request $request)
    {
        $this->validate($request, [
            'password_current' => 'bail|required|current_password',
            'password_new' => 'bail|required|min:6|max:30',
            'password_confirm' => 'bail|required|same:password_new'
        ]);

        Auth::user()->update(['password' => Hash::make($request->password_new)]);
        Auth::logout();

        return redirect()
            ->route('login')
            ->with('success', 'A senha foi atualizada com sucesso!');
    }

    public function delete()
    {
        return view('account.delete');
    }

    public function destroy(Request $request)
    {
        $this->validate($request, [
            'confirm' => 'bail|required',
            'password' => 'bail|required|current_password'
        ]);

        $currenUser = Auth::user();

        Auth::logout();
        $currenUser->delete();

        return redirect()
            ->route('login')
            ->with('success', 'Sua conta foi encerrada com sucesso!');
    }
}
