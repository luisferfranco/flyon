<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tarea extends Model
{
  use SoftDeletes;

  protected $fillable = [
    'asunto',
    'descripcion',
    'estado',
    'prioridad',
    'proyecto_id',
    'user_id',
    'asignado_id',
    'fecha_compromiso',
    'tarea_padre_id',
  ];
  protected $casts = [
    'fecha_compromiso' => 'date',
  ];

  public function proyecto() {
    return $this->belongsTo(Proyecto::class);
  }

  public function user() {
    return $this->belongsTo(User::class);
  }

  public function asignado() {
    return $this->belongsTo(User::class, 'asignado_id');
  }

  public function tareaPadre() {
    return $this->belongsTo(Tarea::class, 'tarea_padre_id');
  }

  public function tareas() {
    return $this->hasMany(Tarea::class, 'tarea_padre_id');
  }

  public function acciones() {
    return $this->hasMany(Accion::class);
  }
}
