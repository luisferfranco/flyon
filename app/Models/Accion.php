<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Accion extends Model
{
  protected $table='acciones';
  protected $fillable = [
    'descripcion',
    'tarea_id',
    'user_id',
  ];

  public function tarea() {
    return $this->belongsTo(Tarea::class);
  }

  public function user() {
    return $this->belongsTo(User::class);
  }
}
