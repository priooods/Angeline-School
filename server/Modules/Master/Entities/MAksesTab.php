<?php

namespace Modules\Master\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MAksesTab extends Model
{
    use HasFactory;

    protected $fillable = ['title','status'];
    
    protected static function newFactory()
    {
        return \Modules\Master\Database\factories\MAksesTabFactory::new();
    }
}
