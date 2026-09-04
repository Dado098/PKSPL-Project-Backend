<?php

namespace Tests\Feature\Api\V1;

use App\Models\Proyek;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProyekApiTest extends TestCase
{
    use DatabaseTransactions;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $existingUser = User::first();
        if ($existingUser) {
            $this->user = $existingUser;
        } else {
            $role = Role::first() ?? Role::create(['nama_role' => 'Peneliti']);
            $this->user = User::create([
                'id_role' => $role->id_role,
                'nama' => 'Test User',
                'email' => 'testuser@example.com',
                'password' => 'password',
                'status' => 'Aktif',
            ]);
        }
    }

    public function test_can_create_proyek_without_shp(): void
    {
        $payload = [
            'nama_proyek' => 'Proyek Wilayah Test',
            'deskripsi' => 'Deskripsi test',
            'alamat_lengkap' => 'Kec. Bogor Tengah',
            'status' => 'Draft',
            'geometry' => [
                'type' => 'Polygon',
                'coordinates' => [
                    [[106.8, -6.6], [106.81, -6.6], [106.81, -6.61], [106.8, -6.61], [106.8, -6.6]]
                ]
            ]
        ];

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/v1/proyek', $payload);

        $response->assertStatus(201)
            ->assertJsonPath('data.nama_proyek', 'Proyek Wilayah Test');

        $this->assertDatabaseHas('proyek', [
            'nama_proyek' => 'Proyek Wilayah Test',
            'id_user' => $this->user->id_user,
        ]);
    }

    public function test_can_create_proyek_with_shp_zip(): void
    {
        Storage::fake('public');

        $zipFile = UploadedFile::fake()->create('area_penelitian.zip', 100, 'application/zip');

        $payload = [
            'nama_proyek' => 'Proyek SHP ZIP Test',
            'deskripsi' => 'Deskripsi SHP',
            'alamat_lengkap' => 'Area Pesisir',
            'status' => 'Draft',
            'geometry' => json_encode([
                'type' => 'Polygon',
                'coordinates' => [
                    [[106.8, -6.6], [106.81, -6.6], [106.81, -6.61], [106.8, -6.61], [106.8, -6.6]]
                ]
            ]),
            'zip' => $zipFile,
        ];

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/v1/proyek', $payload);

        $response->assertStatus(201)
            ->assertJsonPath('data.nama_proyek', 'Proyek SHP ZIP Test');

        $proyek = Proyek::where('nama_proyek', 'Proyek SHP ZIP Test')->first();
        $this->assertNotNull($proyek);
        $this->assertNotNull($proyek->shapefile_files);
        $this->assertArrayHasKey('zip', $proyek->shapefile_files);
    }

    public function test_can_create_proyek_with_shapefile_files_array(): void
    {
        Storage::fake('public');

        $zipFile = UploadedFile::fake()->create('area.zip', 100, 'application/zip');

        $payload = [
            'nama_proyek' => 'Proyek Shapefile Files Array Test',
            'status' => 'Draft',
            'shapefile_files' => [$zipFile],
        ];

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/v1/proyek', $payload);

        $response->assertStatus(201)
            ->assertJsonPath('data.nama_proyek', 'Proyek Shapefile Files Array Test');
    }

    public function test_user_cannot_override_owner_id_user(): void
    {
        $payload = [
            'id_user' => 99999, // Attempt to spoof owner
            'nama_proyek' => 'Proyek Spoofed Owner Test',
            'status' => 'Draft',
        ];

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/v1/proyek', $payload);

        $response->assertStatus(201);

        $proyek = Proyek::where('nama_proyek', 'Proyek Spoofed Owner Test')->first();
        $this->assertNotNull($proyek);
        $this->assertEquals($this->user->id_user, $proyek->id_user);
    }
}
