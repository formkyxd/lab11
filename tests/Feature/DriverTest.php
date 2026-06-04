<?php

namespace Tests\Feature;

use App\Models\Driver;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DriverTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    private function makeVehicle(): Vehicle
    {
        return Vehicle::create([
            'name' => 'КТМ-5 №101',
            'capacity' => 120,
            'type' => 'Tram',
            'line_id' => null,
        ]);
    }

    public function test_admin_can_see_driver_page(): void
    {
        $this->actingAs($this->admin());
        $response = $this->get('/driver');
        $response->assertStatus(200);
    }

    public function test_guest_redirected_from_driver_page(): void
    {
        $response = $this->get('/driver');
        $response->assertRedirect('/login');
    }

    public function test_admin_can_create_driver(): void
    {
        $this->actingAs($this->admin());

        $response = $this->post('/driver/store', [
            'Driver' => [
                'name' => 'Иванов Иван Иванович',
                'birth_date' => '1985-05-15',
                'email' => 'ivanov@test.ru',
                'phone' => '+7 (495) 123-45-67',
            ],
            'vehicle_id' => null,
        ]);

        $response->assertRedirect('/driver');
        $this->assertDatabaseHas('drivers', ['email' => 'ivanov@test.ru']);
    }

    public function test_driver_name_must_contain_only_letters(): void
    {
        $this->actingAs($this->admin());

        $response = $this->post('/driver/store', [
            'Driver' => [
                'name' => 'Ivan123',
                'birth_date' => '1985-05-15',
                'email' => 'test@test.ru',
                'phone' => '+7 (495) 123-45-67',
            ],
        ]);

        $response->assertSessionHasErrors('Driver.name');
    }

    public function test_driver_birth_date_must_be_in_past(): void
    {
        $this->actingAs($this->admin());

        $response = $this->post('/driver/store', [
            'Driver' => [
                'name' => 'Иванов Иван',
                'birth_date' => '2099-01-01',
                'email' => 'test@test.ru',
                'phone' => '+7 (495) 123-45-67',
            ],
        ]);

        $response->assertSessionHasErrors('Driver.birth_date');
    }

    public function test_driver_email_must_be_unique(): void
    {
        $this->actingAs($this->admin());

        Driver::create([
            'name' => 'Первый Водитель',
            'birth_date' => '1980-01-01',
            'email' => 'same@test.ru',
            'phone' => '+7 (495) 111-11-11',
            'vehicle_id' => null,
        ]);

        $response = $this->post('/driver/store', [
            'Driver' => [
                'name' => 'Второй Водитель',
                'birth_date' => '1985-01-01',
                'email' => 'same@test.ru',
                'phone' => '+7 (495) 222-22-22',
            ],
        ]);

        $response->assertSessionHasErrors('Driver.email');
    }

    public function test_vehicle_can_have_only_one_driver(): void
    {
        $this->actingAs($this->admin());
        $vehicle = $this->makeVehicle();

        Driver::create([
            'name' => 'Первый Водитель',
            'birth_date' => '1980-01-01',
            'email' => 'first@test.ru',
            'phone' => '+7 (495) 111-11-11',
            'vehicle_id' => $vehicle->id,
        ]);

        $response = $this->post('/driver/store', [
            'Driver' => [
                'name' => 'Второй Водитель',
                'birth_date' => '1985-01-01',
                'email' => 'second@test.ru',
                'phone' => '+7 (495) 222-22-22',
            ],
            'vehicle_id' => $vehicle->id,
        ]);

        $response->assertSessionHasErrors('vehicle_id');
    }

    public function test_admin_can_delete_driver(): void
    {
        $this->actingAs($this->admin());

        $driver = Driver::create([
            'name' => 'Удаляемый Водитель',
            'birth_date' => '1980-01-01',
            'email' => 'delete@test.ru',
            'phone' => '+7 (495) 333-33-33',
            'vehicle_id' => null,
        ]);

        $response = $this->delete("/drivers/{$driver->id}");

        $response->assertRedirect('/driver');
        $this->assertDatabaseMissing('drivers', ['id' => $driver->id]);
    }

    public function test_admin_can_update_driver(): void
    {
        $this->actingAs($this->admin());

        $driver = Driver::create([
            'name' => 'Старое Имя',
            'birth_date' => '1980-01-01',
            'email' => 'update@test.ru',
            'phone' => '+7 (495) 111-11-11',
            'vehicle_id' => null,
        ]);

        $response = $this->put("/drivers/{$driver->id}", [
            'Driver' => [
                'name' => 'Новое Имя',
                'birth_date' => '1985-05-15',
                'email' => 'update@test.ru',
                'phone' => '+7 (495) 222-22-22',
            ],
            'vehicle_id' => null,
        ]);

        $response->assertRedirect('/driver');
        $this->assertDatabaseHas('drivers', ['name' => 'Новое Имя']);
    }
}
