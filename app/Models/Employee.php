<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    // Arahkan ke tabel di schema PostgreSQL kamu
    protected $table = 'aigenperformancemonitor.employees';

    // Kalau tidak pakai created_at dan updated_at
    public $timestamps = false;

    // Tambahan opsional kalau primary key-nya bukan "id"
    // protected $primaryKey = 'employee_id';
}
