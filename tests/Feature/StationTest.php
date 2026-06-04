<?php

namespace Tests\Feature;

use App\Models\Line;
use App\Models\Station;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StationTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    private function makeLine(): Line
    {
        return Line::create([
            'code' => 'Т-1',
            'start_time_operation' => '06:00:00',
            'end_time_operation' => '23:00:00',
            'type' => 'Tram',
            'map' => '',
        ]);
    }

    public function test_admin_can_see_station_page(): void
    {
        $this->actingAs($this->admin());
        $response = $this->get('/station');
        $response->assertStatus(200);
    }

    public function test_guest_redirected_from_station_page(): void
    {
        $response = $this->get('/station');
        $response->assertRedirect('/login');
    }

    public function test_admin_can_create_station(): void
    {
        $this->actingAs($this->admin());
        $line = $this->makeLine();

        $response = $this->post('/station/store', [
            'Station' => ['name' => 'Центральная'],
            'position_station' => 1,
            'line_id' => $line->id,
        ]);

        $response->assertRedirect('/station');
        $this->assertDatabaseHas('stations', ['name' => 'Центральная']);
    }

    public function test_station_name_is_required(): void
    {
        $this->actingAs($this->admin());

        $response = $this->post('/station/store', [
            'Station' => ['name' => ''],
            'position_station' => 1,
        ]);

        $response->assertSessionHasErrors();
    }

    public function test_station_position_must_be_between_1_and_7(): void
    {
        $this->actingAs($this->admin());

        $response = $this->post('/station/store', [
            'Station' => ['name' => 'Тест'],
            'position_station' => 8,
        ]);

        $response->assertSessionHasErrors('position_station');
    }

    public function test_line_cannot_have_more_than_7_stations(): void
    {
        $this->actingAs($this->admin());
        $line = $this->makeLine();

        for ($i = 1; $i <= 7; $i++) {
            Station::create([
                'name' => "Остановка $i",
                'position_station' => $i,
                'line_id' => $line->id,
            ]);
        }

        $response = $this->post('/station/store', [
            'Station' => ['name' => 'Лишняя'],
            'position_station' => 1,
            'line_id' => $line->id,
        ]);

        $response->assertSessionHasErrors('line_id');
    }

    public function test_position_must_be_unique_per_line(): void
    {
        $this->actingAs($this->admin());
        $line = $this->makeLine();

        Station::create([
            'name' => 'Первая',
            'position_station' => 1,
            'line_id' => $line->id,
        ]);

        $response = $this->post('/station/store', [
            'Station' => ['name' => 'Вторая'],
            'position_station' => 1,
            'line_id' => $line->id,
        ]);

        $response->assertSessionHasErrors('position_station');
    }

    public function test_admin_can_delete_station(): void
    {
        $this->actingAs($this->admin());
        $station = Station::create([
            'name' => 'Удаляемая',
            'position_station' => 1,
            'line_id' => null,
        ]);

        $response = $this->delete("/stations/{$station->id}");

        $response->assertRedirect('/station');
        $this->assertDatabaseMissing('stations', ['id' => $station->id]);
    }

    public function test_admin_can_update_station(): void
    {
        $this->actingAs($this->admin());
        $line = $this->makeLine();
        $station = Station::create([
            'name' => 'Старая',
            'position_station' => 1,
            'line_id' => $line->id,
        ]);

        $response = $this->put("/stations/{$station->id}", [
            'Station' => ['name' => 'Новая'],
            'position_station' => 2,
            'line_id' => $line->id,
        ]);

        $response->assertRedirect('/station');
        $this->assertDatabaseHas('stations', ['name' => 'Новая', 'position_station' => 2]);
    }
}
