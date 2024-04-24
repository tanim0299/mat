<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;


class Menu extends Model
{
    use HasFactory,SoftDeletes;

    protected $guarded = [];
    public static function boot()
    {
        parent::boot();
        static::creating(function($model){
            $model->create_by = Auth::user()->id;
        });
    }

    public function label()
    {
        return $this->belongsTo('App\Models\MenuLabel','label_id');
    }

    public function parent()
    {
        return $this->belongsTo('App\Models\Menu','parent_id');
    }
    public function chilldren()
    {
        return $this->hasMany('App\Models\Menu','parent_id');
    }
}
