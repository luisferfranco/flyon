<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Proyecto extends Model
{
  use SoftDeletes;
  protected $fillable = ['nombre', 'descripcion', 'stub', 'admin_id'];

  public function admin() {
    return $this->belongsTo(User::class, 'admin_id');
  }
  public function tareas() {
    return $this->hasMany(Tarea::class, 'proyecto_id');
  }
}
