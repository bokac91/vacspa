<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use App\Services\VacationCalculator;
use App\Vacation;

class VacationController extends Controller
{
    public function create(Request $request) 
    {
        $request->validate([
            'start_of_vac' => ['required', 'date', 'date_format:Y-m-d'],
            'end_of_vac' => ['required', 'date', 'date_format:Y-m-d', 'after_or_equal:start_of_vac'],
        ]);

        $user = auth()->user();
        $startOfVac = Carbon::parse($request->start_of_vac)->addHours(9);
        $endOfVac = Carbon::parse($request->end_of_vac)->addHours(17);

        $vacationDuration = VacationCalculator::diffInBussinessDays($startOfVac, $endOfVac);

        $errors = [];
        
        if ($vacationDuration == 0) {
            array_push($errors, "Selected vacation contains 0 business days.");
        } 
        
        if ($request->remaining < $vacationDuration) {
            array_push($errors, "You don't have that many days available.");
        }

        if (!VacationCalculator::vacationAvailable($startOfVac, $endOfVac, $user->vacations)) {
            array_push($errors, "One of the selected dates is already taken.");
        }

        if (!empty($errors)) {
            throw ValidationException::withMessages([
                'messages' => $errors
            ]);
        }

        return Vacation::create([
            'owner_id' => $user->id,
            'start_of_vac' => $startOfVac,
            'end_of_vac' => $endOfVac,
        ]);
    }

    public function delete(Request $request) 
    {
        $vacation = Vacation::find($request->id);

        if ($vacation) {
            $vacation->delete();
            return true;
        }

        throw ValidationException::withMessages([
            'deleteRequest' => [
                'Something went wrong.'
            ]
        ]);
    }
}
