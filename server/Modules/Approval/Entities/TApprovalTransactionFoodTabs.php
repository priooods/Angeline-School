<?php

namespace Modules\Approval\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TApprovalTransactionFoodTabs extends Model
{
    use HasFactory;

    protected $fillable = ["t_food_tabs_id","m_status_tabs_id","responded_by","responded_at"];
    
    protected static function newFactory()
    {
        return \Modules\Approval\Database\factories\TApprovalTransactionFoodTabsFactory::new();
    }
}
