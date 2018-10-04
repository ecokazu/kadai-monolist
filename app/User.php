<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    
    //ユーザーがitemにつけているWant と Have のitem一覧を取得
    public function items()
    {
        return $this->belongsToMany(Item::class)->withPivot('type')->withTimestamps();
    }
    
    //ユーザーがitemにつけてい　Wantの item一覧を取得
     public function want_items()
    {
        return $this->items()->where('type', 'want');
    }
    
    
    //ユーザーがitemにつけてい　haveの item一覧を取得
     public function have_items()
    {
        return $this->items()->where('type', 'have');
    }
    
    
     //Wantをつける
     public function want($itemId)
    {
        // 既に Want しているかの確認
        $exist = $this->is_wanting($itemId);

        if ($exist) {
            // 既に Want していれば何もしない
            return false;
        } else {
            // 未 Want であれば Want する
            $this->items()->attach($itemId, ['type' => 'want']);
            return true;
        }
    }
    
     //Wantを外す
    public function dont_want($itemId)
    {
        // 既に Want しているかの確認
        $exist = $this->is_wanting($itemId);

        if ($exist) {
            // 既に Want していれば Want を外す
            \DB::delete("DELETE FROM item_user WHERE user_id = ? AND item_id = ? AND type = 'want'", [$this->id, $itemId]);
        } else {
            // 未 Want であれば何もしない
            return false;
        }
    }
    
    //既にWantしているか確認　//exists()確認しあればtrue を返す
    public function is_wanting($itemIdOrCode)
    {
        //item.idと楽天APIのitemCode　どちらでもチェックできるように
        if (is_numeric($itemIdOrCode)) 
        {
            //is_numeric()で引数が整数が判定
            //整数ならitem.idでのチェック
            $item_id_exists = $this->want_items()->where('item_id', $itemIdOrCode)->exists();
            return $item_id_exists;
            
        } else 
        {
           // 楽天APIのitemCodeのチェック
            $item_code_exists = $this->want_items()->where('code', $itemIdOrCode)->exists();
            return $item_code_exists;
            
        }
    }
    
    
    //haveつける
    public function have($itemId)
    {
        
        //haveしているか確認
        $exist= $this->is_have($itemId);
        
        if($exist)//tureであれば何もしない
        {
            return false;
        }else
        {
            //haveしている一覧を取得して　$itemIdのtypeをhaveに変える
            $this->items()->attach($itemId, ['type' => 'have']);
            return true;
        }
        
        
    }
    
    //have外す
    public function dont_have($itemId)
    {
        // 既にhave しているかの確認
        $exist=$this->is_have($itemId);
        
        //tureなら外す
        if ($exist) {
            \DB::delete("DELETE FROM item_user WHERE user_id = ? AND item_id = ? AND type = 'have'", [$this->id, $itemId]);
        } else {
            // 未 have falseならなにもしない
            return false;
        }
        
    }
    
    
    
    
    
    
    
    //haveしているか確認 有ればtureを返す
    public function is_have($itemIdOrCode)
    {
        //item.idと楽天APIのitemCode　どちらでもチェックできるように
        if (is_numeric($itemIdOrCode)) {
            //is_numeric()で引数が整数が判定
            //整数ならitem.idでのチェック
            
            //have_items()でhaveの一覧取得してチェック
            $item_id_exists = $this->have_items()->where('item_id', $itemIdOrCode)->exists();
            return $item_id_exists;
            
        } else {
           // 楽天APIのitemCodeのチェック
            $item_code_exists = $this->have_items()->where('code', $itemIdOrCode)->exists();
            return $item_code_exists;
        }


    }
    
    
    
    
    
    
    
    
    
}
