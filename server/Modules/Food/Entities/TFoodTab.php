<?php

namespace Modules\Food\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TFoodTab extends Model
{
    use HasFactory;

    protected $fillable = ["m_user_tabs_id","description","price","shop","latitude","longitude",
            "video","images"];
    
    protected static function newFactory()
    {
        return \Modules\Food\Database\factories\TFoodTabFactory::new();
    }
}
