<?php

namespace Modules\Master\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MMenuTab extends Model
{
    use HasFactory;

    protected $fillable = ['title','icon','url','status'];
    
    protected static function newFactory()
    {
        return \Modules\Master\Database\factories\MMenuTabFactory::new();
    }
}
