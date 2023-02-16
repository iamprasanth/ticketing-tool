<?php

namespace SIMS\Models;

use Illuminate\Database\Eloquent\Model;

class Assets extends Model
{
    protected $table = "assets";

    public function getAssetCategory()
    {
         return $this->hasOne('SIMS\Models\AssetsCategories', 'id', 'category_id');
    }

    public function getAssignee()
    {
         return $this->hasOne('SIMS\Models\User', 'id', 'assigned_to');
    }
}
