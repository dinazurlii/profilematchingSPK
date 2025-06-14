<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $table = 'aigenperformancemonitor.groups'; // schema + table
    protected $primaryKey = 'group_id';
    public $timestamps = false;
    protected $guarded = [];
}
