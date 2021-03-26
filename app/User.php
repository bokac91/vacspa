<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Carbon\Carbon;
use App\Vacation;
use App\Services\VacationCalculator;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'first_name', 'last_name', 'email', 'password', 
        'position', 'start_of_work',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'start_of_work' => 'datetime',
    ];

    public function vacations() 
    {
        return $this->hasMany(Vacation::class, 'owner_id');
    }

    public function getVacationDays() 
    {
        $total = floor(VacationCalculator::diffInBussinessDays(Carbon::parse($this->start_of_work), Carbon::now()) / 30) * 2;
        $used = 0;

        foreach ($this->vacations as $vacation) {
            $used += VacationCalculator::diffInBussinessDays($vacation->start_of_vac, $vacation->end_of_vac);
        }

        return compact(
            'total', 
            'used'
        );
    }
}
