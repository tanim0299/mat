<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class ProductItem extends Model
{
    use HasFactory,SoftDeletes;

    protected $guarded =[];

    public static function boot()
    {
        parent::boot();
        static::creating(function($model){
            $model->create_by = Auth::user()->id;
        });
    }

    public static function getActive()
    {
        $data = ProductItem::where('status',1)->get();
        return $data;
    }

    public function category()
    {
        return $this->hasMan('App\Models\Category','item_id');
    }

}
