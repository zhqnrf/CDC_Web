<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuisionerProdi extends Model
{
    use HasFactory ;

    protected $table = "quis_identitas_prodi";
    protected $fillable = [
        'id',
        'nama_prodi'
    ];
}