<?php

namespace Tests\Feature;

use App\Models\Line;
use App\Models\Station;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LineTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    private function user(): User
    {
        return User::factory()->create(['role' => 'user']);
    }

    private function makeLine(array $override = []): Line
    {
        return Line::create(array_merge([
            'code' => 'Т-1',
            'start_time_operation' => '06:00:00',
            'end_time_operation' => '23:00:00',
            'type' => 'Tram',
            'map' => '',
        ], $override));
    }

    // --- index ---

    public function test_guest_can_not_see_line_page(): void
    {
        $response = $this->get('/line');
        $response->assertRedirect('/login');
    }

    public function test_admin_can_see_line_page(): void
    {
        $this->actingAs($this->admin());
        $response = $this->get('/line');
        $response->assertStatus(200);
    }

    public function test_user_can_see_line_page(): void
    {
        $this->actingAs($this->user());
        $response = $this->get('/line');
        $response->assertStatus(200);
    }

    // --- store ---

    public function test_admin_can_create_line(): void
    {
        $this->actingAs($this->admin());

        $response = $this->post('/line/store', [
            'Line' => [
                'code' => 'А-10',
                'start_time_operation' => '06:00:00',
                'end_time_operation' => '22:00:00',
                'type' => 'Bus',
                'map' => '',
            ],
        ]);

        $response->assertRedirect('/line');
        $this->assertDatabaseHas('lines', ['code' => 'А-10']);
    }

    public function test_user_cannot_create_line(): void
    {
        $this->actingAs($this->user());

        $response = $this->post('/line/store', [
            'Line' => [
                'code' => 'А-10',
                'start_time_operation' => '06:00:00',
                'end_time_operation' => '22:00:00',
                'type' => 'Bus',
                'map' => '',
            ],
        ]);

        // Либо 403 либо редирект — не должно создаться
        $this->assertDatabaseMissing('lines', ['code' => 'А-10']);
    }

    public function test_line_code_must_be_unique(): void
    {
        $this->actingAs($this->admin());
        $this->makeLine(['code' => 'Т-1']);

        $response = $this->post('/line/store', [
            'Line' => [
                'code' => 'Т-1',
                'start_time_operation' => '07:00:00',
                'end_time_operation' => '21:00:00',
                'type' => 'Tram',
                'map' => '',
            ],
        ]);

        $response->assertSessionHasErrors();
    }

    public function test_line_start_and_end_time_must_differ(): void
    {
        $this->actingAs($this->admin());

        $response = $this->post('/line/store', [
            'Line' => [
                'code' => 'Т-2',
                'start_time_operation' => '06:00:00',
                'end_time_operation' => '06:00:00',
                'type' => 'Tram',
                'map' => '',
            ],
        ]);

        $response->assertSessionHasErrors();
    }

    public function test_line_type_must_be_valid(): void
    {
        $this->actingAs($this->admin());

        $response = $this->post('/line/store', [
            'Line' => [
                'code' => 'Х-1',
                'start_time_operation' => '06:00:00',
                'end_time_operation' => '22:00:00',
                'type' => 'InvalidType',
                'map' => '',
            ],
        ]);

        $response->assertSessionHasErrors();
    }

    // --- update ---

    public function test_admin_can_update_line(): void
    {
        $this->actingAs($this->admin());
        $line = $this->makeLine();

        $response = $this->put("/lines/{$line->id}", [
            'Line' => [
                'code' => 'Т-99',
                'start_time_operation' => '07:00:00',
                'end_time_operation' => '21:00:00',
                'type' => 'Tram',
                'map' => '',
            ],
        ]);

        $response->assertRedirect('/line');
        $this->assertDatabaseHas('lines', ['code' => 'Т-99']);
    }

    // --- destroy ---

    public function test_admin_can_delete_line(): void
    {
        $this->actingAs($this->admin());
        $line = $this->makeLine();

        $response = $this->delete("/lines/{$line->id}");

        $response->assertRedirect('/line');
        $this->assertDatabaseMissing('lines', ['id' => $line->id]);
    }

    public function test_deleting_line_detaches_stations_and_vehicles(): void
    {
        $this->actingAs($this->admin());
        $line = $this->makeLine();

        Station::create([
            'name' => 'Тест',
            'position_station' => 1,
            'line_id' => $line->id,
        ]);

        Vehicle::create([
            'name' => 'Тест',
            'capacity' => 50,
            'type' => 'Tram',
            'line_id' => $line->id,
        ]);

        $this->delete("/lines/{$line->id}");

        $this->assertDatabaseHas('stations', ['line_id' => null]);
        $this->assertDatabaseHas('vehicles', ['line_id' => null]);
    }
}
