<?php

namespace Modules\Food\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Approval\Entities\TApprovalTransactionFoodTabs;

class TFoodTab extends Model
{
    use HasFactory;

    protected $fillable = ["m_user_tabs_id","description","price","shop","latitude","longitude"];
    
    protected static function newFactory()
    {
        return \Modules\Food\Database\factories\TFoodTabFactory::new();
    }

    public function attachment(){
        return $this->hasMany(TFoodAttachmentTab::class,'t_food_tabs_id','id');
    }

    public function approval(){
        return $this->hasOne(TApprovalTransactionFoodTabs::class,'t_food_tabs_id','id')->orderBy('id','DESC')->limit(1);
    }
}
