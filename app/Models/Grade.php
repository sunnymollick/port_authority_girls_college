<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
   protected $fillable = ['name', 'mark_from', 'mark_upto'];
}
