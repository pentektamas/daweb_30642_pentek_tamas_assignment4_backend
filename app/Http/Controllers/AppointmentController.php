<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AppointmentController extends Controller
{
    public function saveAppointment(Request $request)
    {
        $request->validate([
            'doctor' => 'required',
            'date' => 'required',
            'time' => 'required',
            'userID' => 'required'
        ]);

        $isInserted = DB::table('appointments')
            ->insert(array('doctor' => $request->doctor,
                'date' => $request->date,
                'time' => $request->time,
                'userID' => $request->userID,
            ));

        return response()->json([
            'inserted' => $isInserted
        ]);
    }
}
