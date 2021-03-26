<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Vacation extends Model
{
    protected $fillable = ['owner_id', 'start_of_vac', 'end_of_vac'];

    public function user() 
    {
        return $this->hasOne(User::class);
    }
}
