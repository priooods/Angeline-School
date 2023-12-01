<?php

namespace Modules\Master\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MGenderTab extends Model
{
    use HasFactory;

    protected $fillable = ['title'];
    
    protected static function newFactory()
    {
        return \Modules\Master\Database\factories\MGenderTabFactory::new();
    }
}
