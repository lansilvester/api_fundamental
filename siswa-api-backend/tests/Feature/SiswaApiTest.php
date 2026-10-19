<?php

namespace Tests\Feature;

use App\Models\Siswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SiswaApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Create an authenticated user and return the Bearer token.
     */
    private function authHeader(): array
    {
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        return [
            'Authorization' => "Bearer {$token}",
            'Accept' => 'application/json',
        ];
    }

    public function test_unauthenticated_request_is_rejected(): void
    {
        $this->getJson('/api/v1/siswa')->assertUnauthorized();
    }

    public function test_can_list_siswa(): void
    {
        Siswa::factory()->count(3)->create();

        $this->getJson('/api/v1/siswa', $this->authHeader())
            ->assertOk()
            ->assertJsonCount(3, 'data');
    }

    public function test_can_store_siswa(): void
    {
        $payload = [
            'nis' => '2024099',
            'nama' => 'Joko Santoso',
            'kelas' => 'XI-C',
            'jurusan' => 'MM',
            'alamat' => 'Jl. Cendrawasih No.9',
            'no_telp' => '081312340001',
        ];

        $this->postJson('/api/v1/siswa', $payload, $this->authHeader())
            ->assertCreated()
            ->assertJsonPath('data.nis', $payload['nis']);

        $this->assertDatabaseHas('siswas', ['nama' => 'Joko Santoso']);
    }

    public function test_store_siswa_validates_required_fields(): void
    {
        $this->postJson('/api/v1/siswa', [], $this->authHeader())
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['nis', 'nama', 'kelas', 'jurusan']);
    }

    public function test_can_show_siswa(): void
    {
        $siswa = Siswa::factory()->create();

        $this->getJson("/api/v1/siswa/{$siswa->id}", $this->authHeader())
            ->assertOk()
            ->assertJsonPath('data.id', $siswa->id);
    }

    public function test_can_update_siswa(): void
    {
        $siswa = Siswa::factory()->create();

        $this->putJson(
            "/api/v1/siswa/{$siswa->id}",
            [
                'nis' => $siswa->nis,
                'nama' => 'Nama Diperbarui',
                'kelas' => 'XII-A',
                'jurusan' => $siswa->jurusan,
                'alamat' => $siswa->alamat,
                'no_telp' => $siswa->no_telp,
            ],
            $this->authHeader(),
        )->assertOk();

        $this->assertDatabaseHas('siswas', ['nama' => 'Nama Diperbarui']);
    }

    public function test_can_delete_siswa(): void
    {
        $siswa = Siswa::factory()->create();

        $this->deleteJson("/api/v1/siswa/{$siswa->id}", [], $this->authHeader())
            ->assertOk();

        $this->assertDatabaseMissing('siswas', ['id' => $siswa->id]);
    }
}
