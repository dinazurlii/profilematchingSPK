<?php

// 1. app/Models/Criteria.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Criteria extends Model
{
    use HasFactory;

    protected $table = 'criteria';
    
    protected $fillable = [
        'name'
    ];

    public function roleCriterias()
    {
        return $this->hasMany(RoleCriteria::class);
    }
}

// 2. app/Models/RoleCriteria.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleCriteria extends Model
{
    use HasFactory;

    protected $table = 'role_criteria';
    
    protected $fillable = [
        'role_id',
        'criteria_id',
        'code'
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function criteria()
    {
        return $this->belongsTo(Criteria::class);
    }

    public function subCriterias()
    {
        return $this->hasMany(SubCriteria::class);
    }
}

// 3. app/Models/SubCriteria.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCriteria extends Model
{
    use HasFactory;

    protected $table = 'sub_criteria';
    
    protected $fillable = [
        'description',
        'role_criteria_id'
    ];

    public function roleCriteria()
    {
        return $this->belongsTo(RoleCriteria::class);
    }

    public function scores()
    {
        return $this->hasMany(Score::class);
    }
}

// 4. app/Models/Score.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    use HasFactory;

    protected $table = 'scores';
    
    protected $fillable = [
        'employee_id',
        'sub_criteria_id',
        'score',
        'evaluator_id',
        'evaluated_at'
    ];

    protected $casts = [
        'evaluated_at' => 'datetime'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'nip');
    }

    public function subCriteria()
    {
        return $this->belongsTo(SubCriteria::class);
    }

    public function evaluator()
    {
        return $this->belongsTo(User::class, 'evaluator_id');
    }
}

// 5. app/Models/User.php (Update existing atau create baru)
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    protected $table = 'users';
    
    protected $fillable = [
        'username',
        'password',
        'full_name',
        'role',
        'is_active'
    ];

    protected $hidden = [
        'password'
    ];

    public function scores()
    {
        return $this->hasMany(Score::class, 'evaluator_id');
    }
}

// 6. Update app/Models/Employee.php (tambahkan relationships yang diperlukan)
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $table = 'employees';
    protected $primaryKey = 'nip';
    protected $keyType = 'string';
    public $incrementing = false;
    
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

    protected $casts = [
        'submission' => 'date'
    ];

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function scores()
    {
        return $this->hasMany(Score::class, 'employee_id', 'nip');
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}

// 7. app/Models/Role.php (jika belum ada)
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $table = 'roles';
    
    protected $fillable = [
        'name',
        'division_id'
    ];

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function roleCriterias()
    {
        return $this->hasMany(RoleCriteria::class);
    }
}