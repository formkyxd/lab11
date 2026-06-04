<?php

namespace Tests\Feature;

use App\Models\Line;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VehicleTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    private function makeLine(string $type = 'Tram'): Line
    {
        static $i = 0;

        return Line::create([
            'code' => 'Т-'.(++$i),
            'start_time_operation' => '06:00:00',
            'end_time_operation' => '23:00:00',
            'type' => $type,
            'map' => '',
        ]);
    }

    public function test_admin_can_see_vehicle_page(): void
    {
        $this->actingAs($this->admin());
        $response = $this->get('/vehicle');
        $response->assertStatus(200);
    }

    public function test_guest_redirected_from_vehicle_page(): void
    {
        $response = $this->get('/vehicle');
        $response->assertRedirect('/login');
    }

    public function test_admin_can_create_vehicle(): void
    {
        $this->actingAs($this->admin());

        $response = $this->post('/vehicle/store', [
            'Vehicle' => [
                'name' => 'КТМ-5 №101',
                'capacity' => 120,
                'type' => 'Tram',
            ],
            'line_id' => null,
        ]);

        $response->assertRedirect('/vehicle');
        $this->assertDatabaseHas('vehicles', ['name' => 'КТМ-5 №101']);
    }

    public function test_vehicle_capacity_must_be_positive(): void
    {
        $this->actingAs($this->admin());

        $response = $this->post('/vehicle/store', [
            'Vehicle' => [
                'name' => 'Тест',
                'capacity' => 0,
                'type' => 'Tram',
            ],
        ]);

        $response->assertSessionHasErrors('Vehicle.capacity');
    }

    public function test_vehicle_type_must_be_valid(): void
    {
        $this->actingAs($this->admin());

        $response = $this->post('/vehicle/store', [
            'Vehicle' => [
                'name' => 'Тест',
                'capacity' => 50,
                'type' => 'InvalidType',
            ],
        ]);

        $response->assertSessionHasErrors('Vehicle.type');
    }

    public function test_vehicle_type_must_match_line_type(): void
    {
        $this->actingAs($this->admin());
        $line = $this->makeLine('Tram');

        $response = $this->post('/vehicle/store', [
            'Vehicle' => [
                'name' => 'Автобус №1',
                'capacity' => 80,
                'type' => 'Bus',
            ],
            'line_id' => $line->id,
        ]);

        // Проверяем что не создалось и был редирект назад
        $response->assertRedirect();
        $this->assertDatabaseMissing('vehicles', ['name' => 'Автобус №1']);
    }

    public function test_line_cannot_have_more_than_10_vehicles(): void
    {
        $this->actingAs($this->admin());
        $line = $this->makeLine('Bus');

        for ($i = 1; $i <= 10; $i++) {
            Vehicle::create([
                'name' => "Автобус $i",
                'capacity' => 80,
                'type' => 'Bus',
                'line_id' => $line->id,
            ]);
        }

        $response = $this->post('/vehicle/store', [
            'Vehicle' => [
                'name' => 'Лишний',
                'capacity' => 80,
                'type' => 'Bus',
            ],
            'line_id' => $line->id,
        ]);

        $response->assertSessionHasErrors('line_id');
    }

    public function test_admin_can_delete_vehicle(): void
    {
        $this->actingAs($this->admin());
        $vehicle = Vehicle::create([
            'name' => 'Удаляемый',
            'capacity' => 50,
            'type' => 'Tram',
            'line_id' => null,
        ]);

        $response = $this->delete("/vehicles/{$vehicle->id}");

        $response->assertRedirect('/vehicle');
        $this->assertDatabaseMissing('vehicles', ['id' => $vehicle->id]);
    }

    public function test_admin_can_update_vehicle(): void
    {
        $this->actingAs($this->admin());
        $vehicle = Vehicle::create([
            'name' => 'Старый',
            'capacity' => 50,
            'type' => 'Tram',
            'line_id' => null,
        ]);

        $response = $this->put("/vehicles/{$vehicle->id}", [
            'Vehicle' => [
                'name' => 'Новый',
                'capacity' => 80,
                'type' => 'Tram',
            ],
            'line_id' => null,
        ]);

        $response->assertRedirect('/vehicle');
        $this->assertDatabaseHas('vehicles', ['name' => 'Новый', 'capacity' => 80]);
    }
}
