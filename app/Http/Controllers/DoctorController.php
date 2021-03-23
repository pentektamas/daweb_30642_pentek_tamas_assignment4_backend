<?php


namespace App\Http\Controllers;


use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DoctorController extends Controller
{
    public function getDoctorAppointments(Request $request)
    {
        $request->validate([
            'name' => 'required']);

        $appointments = DB::table('appointments')
            ->select('date', 'time', 'userID')
            ->where(array('doctor' => $request->name))
            ->get();

        $patients = array();
        for ($i = 0; $i < count($appointments); $i++) {
            $patientName = DB::table('users')
                ->select('name')
                ->where(array('id' => $appointments[$i]->userID))
                ->get();
            array_push($patients, $patientName);
        }


        return response()->json([
            'dates' => $appointments,
            'patients' => $patients
        ]);
    }

    public function getAllAppointments(Request $request)
    {
        $request->validate([
            'minim' => 'required',
            'maxim' => 'required',
            'doctor' => 'required']);

        $appointments = DB::table('appointments')
            ->select('date')
            ->where('date', '>=', $request->minim)
            ->where('date', '<=', $request->maxim)
            ->get();

        $diff = round((strtotime($request->maxim) - strtotime($request->minim)) / (60 * 60 * 24));
        $dates = array();
        $nrAppointments = array();
        $currentDate = null;
        try {
            $currentDate = new DateTime($request->minim);
        } catch (\Exception $e) {
        }
        for ($i = 0; $i <= $diff; $i++) {
            $date = clone $currentDate;
            array_push($dates, $date);
            array_push($nrAppointments, 0);
            $currentDate = $currentDate->modify('+1 day');
        }

        for ($j = 0; $j < count($dates); $j++) {
            for ($k = 0; $k < count($appointments); $k++) {
                $dbDate = null;
                try {
                    $dbDate = new DateTime($appointments[$k]->date);
                } catch (\Exception $e) {
                }
                if ($dates[$j] == $dbDate) {
                    $nrAppointments[$j] = $nrAppointments[$j] + 1;
                }
            }
        }
        $finalDates = array();
        for ($j = 0; $j < count($dates); $j++) {
            array_push($finalDates, $dates[$j]->format('d-m-Y'));
        }


        return response()->json([
            'dates' => $finalDates,
            'appointments' => $nrAppointments,
        ]);
    }
}
