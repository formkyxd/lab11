<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_is_admin_returns_true_for_admin_role(): void
    {
        $user = User::factory()->admin()->create();

        $this->assertTrue($user->isAdmin());
        $this->assertFalse($user->isUser());
    }

    public function test_is_user_returns_true_for_user_role(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->assertTrue($user->isUser());
        $this->assertFalse($user->isAdmin());
    }
}
