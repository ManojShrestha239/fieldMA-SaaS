<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'flag' => 'required|in:checkin,checkout,breakstart,breakend',
            'date' => 'required|date_format:Y-m-d',
            'time' => 'required|date_format:H:i:s',
        ]);
        if ($valid->fails()) {
            return response()->json(['status' => 'error', 'message' => $valid->errors()], 422);
        }
        $date = date('Y-m-d');
        $time = date('H:i:s');
        $user = $user = Auth::user();
        $attendance = Attendance::where(['mobileuser_id' => $user->id, 'date' => $date])->first();
        if (! $attendance) {
            $attendance = Attendance::create([
                'mobileuser_id' => $user->id,
                'checkin_date' => $request->time,
                'date' => $request->date,
                'status' => $request->flag,
            ]);
        }
        $attendance_status = new AttendanceStatus;

        $attendance_status->attendance_id = $attendance->id;
        $attendance_status->mobileuser_id = $attendance->mobileuser_id;
        $attendance_status->date = $request->date;
        $attendance_status->time = $request->time;
        $attendance_status->status = $request->flag;

        $attendance_status->save();

        return response()->json(['status' => 'success', 'message' => 'Thank You.', 'flag' => $request->flag]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
