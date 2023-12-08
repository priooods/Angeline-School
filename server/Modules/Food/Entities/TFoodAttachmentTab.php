<?php

namespace Modules\Food\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TFoodAttachmentTab extends Model
{
    use HasFactory;

    protected $fillable = ['t_food_tabs_id','filename','size','type'];
    public $timestamps = false;
    protected $appends = ['url'];
    
    protected static function newFactory()
    {
        return \Modules\Food\Database\factories\TFoodAttachmentTabFactory::new();
    }

    public function getUrlAttribute(){
        if($this->type == 1) {
            return public_path('link_image') . '/' . $this->filename;
        } else {
            return public_path('link_video') . '/' . $this->filename;
        }
    }
}
