<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return view('user', [
            'users' => User::all(),
        ]);
    }

    public function create()
    {
        return view('user', [
            'users' => User::all(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'User.name'       => [
                'required',
                'max:50',
                'regex:/^[A-Za-zА-Яа-яЁё\s\-]+$/u',
            ],
            'User.gender'     => 'nullable|in:M,F,',
            'User.birth_date' => [
                'nullable',
                'date',
                'before:today',
                'after:1900-01-01',
            ],
            'User.email'      => [
                'required',
                'email',
                'max:50',
                'unique:users,email',
            ],
            'User.login'      => [
                'required',
                'max:40',
                'unique:users,login',
                'regex:/^[A-Za-z0-9_\-]+$/',
            ],
            'User.password'   => [
                'required',
                'min:4',
                'max:40',
            ],
            'User.role'       => 'required|in:user,admin',
        ], [
            'User.name.required'      => 'Имя обязательно',
            'User.name.regex'         => 'Имя должно содержать только буквы, пробелы и дефисы',
            'User.birth_date.before'  => 'Дата рождения должна быть в прошлом',
            'User.birth_date.after'   => 'Некорректная дата рождения',
            'User.email.unique'       => 'Пользователь с таким email уже существует',
            'User.login.unique'       => 'Этот логин уже занят',
            'User.login.regex'        => 'Логин может содержать только латинские буквы, цифры, _ и -',
            'User.password.min'       => 'Пароль должен быть не менее 4 символов',
            'User.role.in'            => 'Недопустимая роль',
        ]);

        User::create([
            'name'       => $request->input('User.name'),
            'gender'     => $request->input('User.gender', ''),
            'birth_date' => $request->input('User.birth_date'),
            'email'      => $request->input('User.email'),
            'login'      => $request->input('User.login'),
            'password'   => Hash::make($request->input('User.password')),
            'role'       => $request->input('User.role', 'user'),
        ]);

        return redirect()->route('user')->with('success', 'Пользователь успешно добавлен');
    }

    public function edit(User $user)
    {
        return view('user', [
            'user'  => $user,
            'users' => User::all(),
        ]);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'User.name'       => [
                'required',
                'max:50',
                'regex:/^[A-Za-zА-Яа-яЁё\s\-]+$/u',
            ],
            'User.gender'     => 'nullable|in:M,F,',
            'User.birth_date' => [
                'nullable',
                'date',
                'before:today',
                'after:1900-01-01',
            ],
            'User.email'      => [
                'required',
                'email',
                'max:50',
                'unique:users,email,' . $user->id,
            ],
            'User.login'      => [
                'required',
                'max:40',
                'unique:users,login,' . $user->id,
                'regex:/^[A-Za-z0-9_\-]+$/',
            ],
            'User.password'   => 'nullable|min:4|max:40',
            'User.role'       => 'required|in:user,admin',
        ], [
            'User.name.regex'        => 'Имя должно содержать только буквы, пробелы и дефисы',
            'User.birth_date.before' => 'Дата рождения должна быть в прошлом',
            'User.birth_date.after'  => 'Некорректная дата рождения',
            'User.email.unique'      => 'Пользователь с таким email уже существует',
            'User.login.unique'      => 'Этот логин уже занят',
            'User.login.regex'       => 'Логин может содержать только латинские буквы, цифры, _ и -',
            'User.password.min'      => 'Пароль должен быть не менее 4 символов',
        ]);

        $data = [
            'name'       => $request->input('User.name'),
            'gender'     => $request->input('User.gender', ''),
            'birth_date' => $request->input('User.birth_date'),
            'email'      => $request->input('User.email'),
            'login'      => $request->input('User.login'),
            'role'       => $request->input('User.role', 'user'),
        ];

        if ($request->filled('User.password')) {
            $data['password'] = Hash::make($request->input('User.password'));
        }

        $user->update($data);

        return redirect()->route('user')->with('success', 'Пользователь обновлён');
    }

    public function destroy(User $user)
    {
        // Нельзя удалить самого себя
        if ($user->id === auth()->id()) {
            return back()->withErrors(['user' => 'Нельзя удалить собственный аккаунт']);
        }

        $user->delete();
        return redirect()->route('user')->with('success', 'Пользователь удалён');
    }

    public function show(User $user) {}
}