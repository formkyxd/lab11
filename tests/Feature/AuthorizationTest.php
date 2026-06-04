<?php

namespace Tests\Feature;

use App\Models\Driver;
use App\Models\Line;
use App\Models\Station;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->admin()->create();
    }

    private function regularUser(): User
    {
        return User::factory()->create(['role' => 'user']);
    }

    public function test_regular_user_cannot_create_user(): void
    {
        $this->actingAs($this->regularUser());

        $this->post('/user/store', [
            'User' => [
                'name' => 'Новый Пользователь',
                'email' => 'new@test.ru',
                'login' => 'newuser',
                'password' => 'pass1234',
                'role' => 'user',
            ],
        ]);

        $this->assertDatabaseMissing('users', ['login' => 'newuser']);
    }

    public function test_regular_user_cannot_delete_station(): void
    {
        $this->actingAs($this->regularUser());

        $station = Station::create([
            'name' => 'Остановка',
            'position_station' => 1,
            'line_id' => null,
        ]);

        $this->delete("/stations/{$station->id}");

        $this->assertDatabaseHas('stations', ['id' => $station->id]);
    }

    public function test_regular_user_cannot_delete_vehicle(): void
    {
        $this->actingAs($this->regularUser());

        $vehicle = Vehicle::create([
            'name' => 'Трамвай',
            'capacity' => 50,
            'type' => 'Tram',
            'line_id' => null,
        ]);

        $this->delete("/vehicles/{$vehicle->id}");

        $this->assertDatabaseHas('vehicles', ['id' => $vehicle->id]);
    }

    public function test_regular_user_cannot_delete_driver(): void
    {
        $this->actingAs($this->regularUser());

        $driver = Driver::create([
            'name' => 'Водитель',
            'birth_date' => '1980-01-01',
            'email' => 'driver@test.ru',
            'phone' => '+7 (495) 111-11-11',
            'vehicle_id' => null,
        ]);

        $this->delete("/drivers/{$driver->id}");

        $this->assertDatabaseHas('drivers', ['id' => $driver->id]);
    }

    public function test_regular_user_cannot_delete_line(): void
    {
        $this->actingAs($this->regularUser());

        $line = Line::create([
            'code' => 'Т-1',
            'start_time_operation' => '06:00:00',
            'end_time_operation' => '23:00:00',
            'type' => 'Tram',
            'map' => '',
        ]);

        $this->delete("/lines/{$line->id}");

        $this->assertDatabaseHas('lines', ['id' => $line->id]);
    }

    public function test_authenticated_user_can_see_station_list(): void
    {
        $this->actingAs($this->regularUser());

        $response = $this->get('/station/list');

        $response->assertStatus(200);
    }
}
