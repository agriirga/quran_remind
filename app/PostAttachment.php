<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostAttachment extends Model
{
    use SoftDeletes;
    
    protected $dates = ['deleted_at'];
    protected $fillable = ['attachment_title','path_location','post_id'];
    
    public function post()
    {
        return $this->belongsTo('App\Post');
    }

}
