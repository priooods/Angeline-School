<?php

namespace Modules\Food\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TFoodAttachmentTab extends Model
{
    use HasFactory;

    protected $fillable = ['t_food_tabs_id','filename','size'];
    
    protected static function newFactory()
    {
        return \Modules\Food\Database\factories\TFoodAttachmentTabFactory::new();
    }
}
