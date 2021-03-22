<?php

use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;
use Illuminate\Support\Facades\DB;

class DoctorControllerTest extends TestCase
{
    public function testGetDoctorAppointmentsTest()
    {
        $request = Request::create('/doctorAppointments', 'POST', [
            'name' => 'Popescu Mihai'
        ]);

        $controller = new \App\Http\Controllers\DoctorController();
        $response = $controller->getDoctorAppointments($request);
        echo($response);
    }
}
