<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{

    protected $fillable = ['bio'];
    protected $appends = ['avatar_url'];

    public function getAvatarUrlAttribute(){
//        return Storage::url('avatars/'.$this->id.'/'.$this->avatar);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

}
