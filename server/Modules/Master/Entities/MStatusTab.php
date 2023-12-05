<?php

namespace Modules\Master\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MStatusTab extends Model
{
    use HasFactory;

    protected $fillable = ['title','status'];
    protected $timestamps = false;
    
    protected static function newFactory()
    {
        return \Modules\Master\Database\factories\MStatusTabFactory::new();
    }
}
