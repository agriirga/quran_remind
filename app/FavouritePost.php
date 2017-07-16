<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FavouritePost extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = ['post_id','user_id','act_id'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function post()
    {
        return $this->belongsTo('App\Post');
    }

    public function action()
    {
        return $this->belongsTo('App\PostAction');
    }
}
