<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    // Arahkan ke tabel di schema PostgreSQL kamu
    protected $table = 'aigenperformancemonitor.employees';

    // Kalau tidak pakai created_at dan updated_at
    public $timestamps = false;

    // Set primary key ke 'nip' sesuai database schema
    protected $primaryKey = 'nip';
    protected $keyType = 'string';
    public $incrementing = false;

    // Add fillable untuk mass assignment
    protected $fillable = [
        'nip',
        'name',
        'group_id',
        'email',
        'division_id',
        'role_id',
        'scores_id',
        'status',
        'submission'
    ];

    // Cast untuk date fields
    protected $casts = [
        'submission' => 'date'
    ];

    public function division()
    {
        return $this->belongsTo(Division::class, 'division_id');
    }
    
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    // Helper methods untuk status
    public function getStatusBadgeAttribute()
    {
        return $this->status === 'completed' ? 'success' : 'danger';
    }

    public function getStatusTextAttribute()
    {
        return $this->status === 'completed' ? 'Completed' : 'Need Review';
    }
}