<?php

namespace Modules\User\Entities;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MUserTab extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = ['email','password','repassword','fullname','is_activated','is_deleted'];
    protected $hidden = ['created_at','updated_at','password','repassword'];
    
    protected static function newFactory()
    {
        return \Modules\User\Database\factories\MUserTabFactory::new();
    }
}
