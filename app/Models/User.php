<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
  /** @use HasFactory<\Database\Factories\UserFactory> */
  use HasFactory, Notifiable;
  use SoftDeletes;

  protected $fillable = ['name', 'email', 'password', 'rol', 'imagen', ];
  protected $hidden = ['password', 'remember_token', ];

  /**
   * Get the attributes that should be cast.
   *
   * @return array<string, string>
   */
  protected function casts(): array
  {
    return [
      'email_verified_at' => 'datetime',
      'password' => 'hashed',
    ];
  }

  public function proyectos() {
    return $this->hasMany(Proyecto::class, 'admin_id');
  }
  public function tareas() {
    return $this->hasMany(Tarea::class, 'user_id');
  }
  public function tareasAsignadas() {
    return $this->hasMany(Tarea::class, 'asignado_id');
  }
}
