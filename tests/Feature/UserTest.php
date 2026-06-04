<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    public function test_admin_can_see_user_page(): void
    {
        $this->actingAs($this->admin());
        $response = $this->get('/user');
        $response->assertStatus(200);
    }

    public function test_guest_redirected_from_user_page(): void
    {
        $response = $this->get('/user');
        $response->assertRedirect('/login');
    }

    public function test_admin_can_create_user(): void
    {
        $this->actingAs($this->admin());

        $response = $this->post('/user/store', [
            'User' => [
                'name' => 'Иванов Иван',
                'gender' => 'M',
                'email' => 'new@test.ru',
                'login' => 'newuser',
                'password' => 'pass1234',
                'role' => 'user',
            ],
        ]);

        $response->assertRedirect('/user');
        $this->assertDatabaseHas('users', ['login' => 'newuser']);
    }

    public function test_user_login_must_be_unique(): void
    {
        $this->actingAs($this->admin());

        User::factory()->create(['login' => 'existinglogin']);

        $response = $this->post('/user/store', [
            'User' => [
                'name' => 'Тест Тест',
                'email' => 'unique@test.ru',
                'login' => 'existinglogin',
                'password' => 'pass1234',
                'role' => 'user',
            ],
        ]);

        $response->assertSessionHasErrors('User.login');
    }

    public function test_user_email_must_be_unique(): void
    {
        $this->actingAs($this->admin());

        User::factory()->create(['email' => 'existing@test.ru']);

        $response = $this->post('/user/store', [
            'User' => [
                'name' => 'Тест Тест',
                'email' => 'existing@test.ru',
                'login' => 'uniquelogin',
                'password' => 'pass1234',
                'role' => 'user',
            ],
        ]);

        $response->assertSessionHasErrors('User.email');
    }

    public function test_user_name_must_contain_only_letters(): void
    {
        $this->actingAs($this->admin());

        $response = $this->post('/user/store', [
            'User' => [
                'name' => 'User123',
                'email' => 'test@test.ru',
                'login' => 'testlogin',
                'password' => 'pass1234',
                'role' => 'user',
            ],
        ]);

        $response->assertSessionHasErrors('User.name');
    }

    public function test_admin_can_delete_user(): void
    {
        $this->actingAs($this->admin());

        $user = User::factory()->create();

        $response = $this->delete("/users/{$user->id}");

        $response->assertRedirect('/user');
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_admin_cannot_delete_himself(): void
    {
        $admin = $this->admin();
        $this->actingAs($admin);

        $response = $this->delete("/users/{$admin->id}");

        $response->assertSessionHasErrors();
        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    public function test_password_is_hashed_on_create(): void
    {
        $this->actingAs($this->admin());

        $this->post('/user/store', [
            'User' => [
                'name' => 'Иванов Иван',
                'email' => 'hash@test.ru',
                'login' => 'hashuser',
                'password' => 'mypassword',
                'role' => 'user',
            ],
        ]);

        $user = User::where('login', 'hashuser')->first();
        $this->assertTrue(Hash::check('mypassword', $user->password));
    }

    public function test_admin_can_update_user(): void
    {
        $this->actingAs($this->admin());
        $user = User::factory()->create(['name' => 'Старое Имя']);

        $response = $this->put("/users/{$user->id}", [
            'User' => [
                'name' => 'Новое Имя',
                'email' => $user->email,
                'login' => $user->login,
                'role' => 'user',
            ],
        ]);

        $response->assertRedirect('/user');
        $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => 'Новое Имя']);
    }
}
