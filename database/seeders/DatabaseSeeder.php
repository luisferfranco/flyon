<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Proyecto;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    User::factory()->create([
      'name'  => 'Luis Franco',
      'email' => 'luisferfranco@gmail.com',
    ]);
    Proyecto::create([
      'nombre'      => '📫 Buzón',
      'descripcion' => 'Captura rápida de ideas',
      'stub'        => 'inbox',
      'admin_id'    => 1,
    ]);
    // User::factory(10)->create();

  }
}
