<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Contact;
use App\Models\User;


class ContactTest extends TestCase
{
    use RefreshDatabase; // Limpa o banco após cada teste

    /** @test */
    public function it_creates_a_contact()
    {
        $user = User::factory()->create(); // Cria um usuário para autenticação
        $token = $user->createToken('TestToken')->plainTextToken;

        $data = [
            'name' => 'João Silva',
            'cpf' => '12345678909',
            'phone' => '(11) 91234-5678',
            'cep' => '01001-000',
            'address' => 'Praça da Sé',
        ];

        $response = $this->withHeader('Authorization', "Bearer {$token}")
                        ->postJson('/api/contacts', $data);

        $response->assertStatus(201)
                ->assertJson([
                    'message' => 'Contato cadastrado com sucesso!',
                    'data' => ['name' => 'João Silva']
                ]);

        $this->assertDatabaseHas('contacts', ['cpf' => '12345678909']);
    }

    /** @test */
    public function it_lists_contacts()
    {
        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        Contact::factory()->count(3)->create();

        $response = $this->withHeader('Authorization', "Bearer {$token}")
                        ->getJson('/api/contacts');

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


    /** @test */
    public function it_updates_a_contact()
    {
        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        $contact = Contact::factory()->create();

        $updatedData = ['name' => 'João Atualizado'];

        $response = $this->withHeader('Authorization', "Bearer {$token}")
                        ->putJson("/api/contacts/{$contact->id}", $updatedData);

        $response->assertStatus(200)
                ->assertJson([
                    'message' => 'Contato atualizado com sucesso!',
                    'data' => ['name' => 'João Atualizado']
                ]);

        $this->assertDatabaseHas('contacts', ['id' => $contact->id, 'name' => 'João Atualizado']);
    }


    /** @test */
    public function it_deletes_a_contact()
    {
        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        $contact = Contact::factory()->create();

        $response = $this->withHeader('Authorization', "Bearer {$token}")
                        ->deleteJson("/api/contacts/{$contact->id}");

        $response->assertStatus(200)
                ->assertJson(['message' => 'Contato deletado com sucesso!']);

        $this->assertDatabaseMissing('contacts', ['id' => $contact->id]);
    }

}
