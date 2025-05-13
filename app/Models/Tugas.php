<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tugas extends Model
{
    use HasFactory;

    protected $table = 'tugas';

    protected $fillable = [
        'id_user',
        'nama_tugas',
        'deadline',
        'file',
        'status',
    ];

    protected $casts = [
        'deadline' => 'datetime',
    ];

    // Relasi dengan model User
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    // Aksesor untuk status
    public function getStatusAttribute($value)
    {
        return ucfirst($value);
    }
}