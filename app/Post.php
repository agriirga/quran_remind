<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use SoftDeletes;
    
    protected $dates = ['deleted_at'];


    protected $fillable = ['author','post_title','post_body'];
    
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
