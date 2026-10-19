<?php

namespace App\Http\Controllers;

use App\Http\Resources\SiswaResource;
use App\Models\Siswa;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        $siswaList = Siswa::query()
            ->when(request('q'), function ($query, $q) {
                $query->where('nama', 'like', "%{$q}%")
                    ->orWhere('nis', 'like', "%{$q}%");
            })
            ->latest()
            ->paginate(10);

        return SiswaResource::collection($siswaList);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nis' => ['required', 'string', 'max:20', 'unique:siswas,nis'],
            'nama' => ['required', 'string', 'max:150'],
            'kelas' => ['required', 'string', 'max:20'],
            'jurusan' => ['required', 'string', 'max:50'],
            'alamat' => ['nullable', 'string', 'max:255'],
            'no_telp' => ['nullable', 'string', 'max:20'],
        ]);

        $siswa = Siswa::create($validated);

        return (new SiswaResource($siswa))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Siswa $siswa): SiswaResource
    {
        return new SiswaResource($siswa);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Siswa $siswa): SiswaResource
    {
        $validated = $request->validate([
            'nis' => ['required', 'string', 'max:20', 'unique:siswas,nis,'.$siswa->id],
            'nama' => ['required', 'string', 'max:150'],
            'kelas' => ['required', 'string', 'max:20'],
            'jurusan' => ['required', 'string', 'max:50'],
            'alamat' => ['nullable', 'string', 'max:255'],
            'no_telp' => ['nullable', 'string', 'max:20'],
        ]);

        $siswa->update($validated);

        return new SiswaResource($siswa);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Siswa $siswa): JsonResponse
    {
        $siswa->delete();

        return response()->json([
            'message' => 'Data siswa berhasil dihapus.',
        ], 200);
    }
}
