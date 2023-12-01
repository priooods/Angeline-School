<?php

namespace Modules\User\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TUserDetailTab extends Model
{
    use HasFactory;

    protected $fillable = ['m_user_tab_id','age','m_gender_tab_id','city',''];
    
    protected static function newFactory()
    {
        return \Modules\User\Database\factories\TUserDetailTabFactory::new();
    }
}
