<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pregunta extends Model
{
    use HasFactory;
    protected $table = 'preguntes';
    protected $fillable = [
        'pregunta',
        'dificultat_id',
        'tema_id'
        
    ];
}
