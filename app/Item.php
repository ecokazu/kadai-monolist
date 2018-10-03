<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    //書き換え可能カラム
    protected $fillable = ['code', 'name', 'url', 'image_url'];
    
    public function users()
    {
       //itemについている Want と Have 両方のUser 一覧を取得
        return $this->belongsToMany(User::class)->withPivot('type')->withTimestamps();
    }
    
     public function want_users()
    {
        //Want User 一覧を取得
        return $this->users()->where('type', 'want');
    }
}
