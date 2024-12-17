<?php

namespace Database\Factories;

use App\Models\Contact;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactFactory extends Factory
{
    protected $model = Contact::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'cpf' => $this->faker->unique()->numerify('###########'),
            'phone' => $this->faker->phoneNumber,
            'cep' => '01001-000',
            'address' => $this->faker->streetAddress,
            'latitude' => $this->faker->latitude,
            'longitude' => $this->faker->longitude,
        ];
    }

    public function it_lists_contacts()
    {
        $user = User::factory()->create();
        Contact::factory()->count(3)->create();

        $response = $this->actingAs($user, 'api')->getJson('/api/contacts');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'message',
                    'data' => [
                        'data' => [
                            '*' => ['id', 'name', 'cpf', 'phone', 'address', 'cep']
                        ]
                    ]
                ]);
    }

}
