<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pincode extends Model
{
    use HasFactory;
	
	protected $table = 'pincode';
	
    protected $fillable = [
	  'pincode_name',
	  'is_publish'
    ];	
}
