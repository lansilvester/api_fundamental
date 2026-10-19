<?php

namespace Database\Seeders;

use App\Models\Siswa;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User admin untuk demo
        User::factory()->create([
            'name' => 'Admin Sekolah',
            'email' => 'admin@sekolah.test',
            'password' => 'password',
        ]);

        // Data siswa contoh
        $siswaSamples = [
            ['nis' => '2024001', 'nama' => 'Ahmad Fauzi', 'kelas' => 'XI-A', 'jurusan' => 'RPL', 'alamat' => 'Jl. Merdeka No.1', 'no_telp' => '081234567890'],
            ['nis' => '2024002', 'nama' => 'Bella Putri', 'kelas' => 'XI-A', 'jurusan' => 'RPL', 'alamat' => 'Jl. Sudirman No.2', 'no_telp' => '081298765431'],
            ['nis' => '2024003', 'nama' => 'Candra Wijaya', 'kelas' => 'XI-B', 'jurusan' => 'TKJ', 'alamat' => 'Jl. Gatot Subroto No.3', 'no_telp' => '081377766655'],
            ['nis' => '2024004', 'nama' => 'Dinda Ayu', 'kelas' => 'XI-B', 'jurusan' => 'TKJ', 'alamat' => 'Jl. Thamrin No.4', 'no_telp' => '081455544433'],
            ['nis' => '2024005', 'nama' => 'Eko Prasetyo', 'kelas' => 'XI-C', 'jurusan' => 'MM', 'alamat' => 'Jl. Diponegoro No.5', 'no_telp' => '081512345678'],
            ['nis' => '2024006', 'nama' => 'Fitri Handayani', 'kelas' => 'XI-C', 'jurusan' => 'MM', 'alamat' => 'Jl. Jend. A. Yani No.6', 'no_telp' => '081698765432'],
            ['nis' => '2024007', 'nama' => 'Galih Saputra', 'kelas' => 'XI-A', 'jurusan' => 'RPL', 'alamat' => 'Jl. Pahlawan No.7', 'no_telp' => '081788899900'],
            ['nis' => '2024008', 'nama' => 'Hana Permata', 'kelas' => 'XI-B', 'jurusan' => 'TKJ', 'alamat' => 'Jl. Veteran No.8', 'no_telp' => '081912345678'],
        ];

        foreach ($siswaSamples as $data) {
            Siswa::create($data);
        }
    }
}
