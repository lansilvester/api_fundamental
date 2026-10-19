<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nis',
        'nama',
        'kelas',
        'jurusan',
        'alamat',
        'no_telp',
    ];

    /**
     * Return the cache key that identifies the model.
     */
    public function getCacheKey(): string
    {
        return sprintf('%s:%s', $this->getTable(), $this->getKey());
    }
}
