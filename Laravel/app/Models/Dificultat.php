<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dificultat extends Model
{
    use HasFactory;
    protected $table = 'dificultats';
    protected $fillable = ['id', 'nom_dificultat'];
}
