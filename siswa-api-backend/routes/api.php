<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\SiswaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Di sinilah aplikasi frontend (React) berbicara dengan backend melalui
| HTTP Request & Response. Endpoint diawali prefix `/api`.
|
| CONTOH PEMBELAJARAN: SEMUA route ditulis manual satu per satu
| agar terlihat jelas bagaimana RESTful API bekerja di balik layar.
|
| Di production, 5 route CRUD siswa bisa diringkas dengan 1 baris:
|
|     Route::apiResource('siswa', SiswaController::class);
|
| `apiResource` hanyalah shortcut yang mendaftarkan tepat 5 route
| berikut (dengan nama route siswa.index, siswa.store, dst):
|
|     GET    /siswa          → index   (ambil semua / cari)
|     POST   /siswa          → store   (tambah baru)
|     GET    /siswa/{siswa}  → show    (ambil satu)
|     PUT    /siswa/{siswa}  → update  (ubah data)
|     DELETE /siswa/{siswa}  → destroy (hapus data)
|
| Catatan: `apiResource` mengecualikan `create` dan `edit` karena kedua
| aksi tersebut hanya mengembalikan form HTML — tidak dibutuhkan di API.
|
| Route yang berupa AKSI KHUSUS (bukan CRUD) TIDAK bisa dipakai dengan
| apiResource dan harus ditulis manual, contohnya login/logout/register.
|
*/

Route::prefix('v1')->group(function () {

    /*
    |----------------------------------------------------------------------
    | AUTH — aksi khusus (bukan CRUD, jadi ditulis manual)
    |----------------------------------------------------------------------
    | POST /register → buat akun baru
    | POST /login    → dapatkan token Sanctum
    */

    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    // Butuh token (auth:sanctum) — semua route di bawah ini terproteksi
    Route::middleware('auth:sanctum')->group(function () {

        /*
        |----------------------------------------------------------------------
        | AUTH (terproteksi)
        |----------------------------------------------------------------------
        | GET  /me     → data user yang sedang login
        | POST /logout → cabut token (revoke)
        */

        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);

        /*
        |----------------------------------------------------------------------
        | SISWA — CRUD, ditulis manual 1 per 1 untuk pembelajaran
        |----------------------------------------------------------------------
        |
        | 1. INDEX — GET /api/v1/siswa
        |    Ambil daftar siswa (ada fitur pencarian via ?q=)
        |    Respons: 200 + JSON berisi data + pagination
        |
        */
        Route::get('/siswa', [SiswaController::class, 'index'])
            ->name('siswa.index');

        /*
        | 2. STORE — POST /api/v1/siswa
        |    Tambah data siswa baru dari body JSON
        |    Respons: 201 (Created) + data baru
        |    Error umum: 422 (validasi gagal), 401 (belum login)
        |
        */
        Route::post('/siswa', [SiswaController::class, 'store'])
            ->name('siswa.store');

        /*
        | 3. SHOW — GET /api/v1/siswa/{id}
        |    Ambil SATU siswa berdasarkan ID
        |    Contoh: GET /api/v1/siswa/3
        |    Respons: 200 + data satu siswa, atau 404 jika tidak ada
        |    Catatan: {siswa} = route model binding otomatis (Laravel
        |    mencari model Siswa berdasarkan ID; jika tidak ada → 404)
        |
        */
        Route::get('/siswa/{siswa}', [SiswaController::class, 'show'])
            ->name('siswa.show');

        /*
        | 4. UPDATE — PUT /api/v1/siswa/{id}
        |    Ubah data siswa dari body JSON
        |    PUT   = ganti seluruh data (full replace)
        |    PATCH = ubah sebagian (Laravel otomatis menerima keduanya)
        |    Respons: 200 + data terbaru, atau 422 jika validasi gagal
        |
        */
        Route::put('/siswa/{siswa}', [SiswaController::class, 'update'])
            ->name('siswa.update');

        /*
        | 5. DESTROY — DELETE /api/v1/siswa/{id}
        |    Hapus satu siswa berdasarkan ID
        |    Respons: 200 + pesan sukses, atau 404 jika tidak ada
        |
        */
        Route::delete('/siswa/{siswa}', [SiswaController::class, 'destroy'])
            ->name('siswa.destroy');

        /*
        |----------------------------------------------------------------------
        | VERSI RINGKAS (untuk production)
        |----------------------------------------------------------------------
        | Kelima route manual di atas TEPAT SAMA dengan satu baris ini:
        |
        |     Route::apiResource('siswa', SiswaController::class);
        |
        | Bedanya: versi manual di atas menulis ->name() sendiri agar
        | nama route tetap sama (siswa.index, siswa.store, dst) sehingga
        | test dan frontend tidak perlu berubah.
        |
        | Tapi route manual DIPERLUKAN untuk endpoint seperti:
        |     POST /login, POST /register, GET /me, POST /logout
        | karena itu aksi khusus, bukan CRUD resource.
        |
        |----------------------------------------------------------------------
        | FILTER OPSIONAL apiResource
        |----------------------------------------------------------------------
        | Kalau hanya butuh sebagian aksi:
        |
        |     Route::apiResource('siswa', SiswaController::class,
        |         only: ['index', 'show']);   // hanya baca data
        |
        |     Route::apiResource('siswa', SiswaController::class,
        |         except: ['destroy']);       // semua kecuali hapus
        |
        */
    });
});
