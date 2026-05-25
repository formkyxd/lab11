<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'       => [
                'required',
                'string',
                'max:50',
                'regex:/^[A-Za-zА-Яа-яЁё\s\-]+$/u',
            ],
            'gender'     => ['nullable', 'in:M,F,'],
            'birth_date' => [
                'nullable',
                'date',
                'before:today',
                'after:1900-01-01',
            ],
            'email'      => [
                'required',
                'string',
                'email',
                'max:50',
                'unique:users,email',
            ],
            'login'      => [
                'required',
                'string',
                'max:40',
                'unique:users,login',
                'regex:/^[A-Za-z0-9_\-]+$/',
            ],
            'password'   => [
                'required',
                'confirmed',
                'min:4',
                'max:40',
            ],
        ], [
            'name.required'      => 'Имя обязательно для заполнения',
            'name.max'           => 'Имя не должно превышать 50 символов',
            'name.regex'         => 'Имя должно содержать только буквы, пробелы и дефисы',
            'birth_date.date'    => 'Некорректный формат даты рождения',
            'birth_date.before'  => 'Дата рождения должна быть в прошлом',
            'birth_date.after'   => 'Некорректная дата рождения',
            'email.required'     => 'Email обязателен для заполнения',
            'email.email'        => 'Введите корректный email адрес',
            'email.max'          => 'Email не должен превышать 50 символов',
            'email.unique'       => 'Пользователь с таким email уже зарегистрирован',
            'login.required'     => 'Логин обязателен для заполнения',
            'login.max'          => 'Логин не должен превышать 40 символов',
            'login.unique'       => 'Этот логин уже занят',
            'login.regex'        => 'Логин может содержать только латинские буквы, цифры, _ и -',
            'password.required'  => 'Пароль обязателен для заполнения',
            'password.confirmed' => 'Пароли не совпадают',
            'password.min'       => 'Пароль должен быть не менее 4 символов',
            'password.max'       => 'Пароль не должен превышать 40 символов',
        ]);

        $user = User::create([
            'name'       => $request->name,
            'gender'     => $request->gender ?? '',
            'birth_date' => $request->birth_date,
            'email'      => $request->email,
            'login'      => $request->login,
            'password'   => Hash::make($request->password),
            'role'       => 'user',
        ]);

        event(new Registered($user));
        Auth::login($user);

        return redirect()->route('home');
    }
}