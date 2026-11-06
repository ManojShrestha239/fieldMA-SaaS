<?php

namespace App\Http\Controllers\API\v3;

use App\Http\Controllers\Controller;
use App\Models\Purpose;
use App\Models\Session;
use App\Models\VisitPurpose;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SessionController extends Controller
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
            'data' => 'required|array',
            'data.*.address' => 'required|string|max:191',
            'data.*.dealer_name' => 'required|string|max:191',
            'data.*.user_id' => 'required|exists:mobileusers,id',
            'data.*.contact' => 'required',
            'data.*.purpose_id' => 'required',
            'data.*.date' => 'date|date_format:Y-m-d',
            'data.*.start_time' => 'date_format:H:i',
            'data.*.end_time' => 'date_format:H:i|after:data.*.start_time',
            'data.*.remarks' => 'required|string|max:255',
        ]);
        if ($valid->fails()) {
            return response()->json(['status' => 'error', 'message' => $valid->errors()], 422);
        }
        foreach ($request->data as $unlisted) {
            $purpose_id = explode(' :: ', $unlisted['purpose_id']);
            if (Purpose::whereIn('id', $purpose_id)->count() == 0) {
                continue;
            }
            $negotiation = new Session;

            $negotiation->user_id = Auth::user()->id;
            $negotiation->dealer_name = $unlisted['dealer_name'];
            $negotiation->dealer_address = $unlisted['address'];
            $negotiation->contact = $unlisted['contact'];
            $negotiation->purpose_id = $unlisted['purpose_id'];
            $negotiation->start_time = $unlisted['start_time'];
            $negotiation->end_time = $unlisted['end_time'];
            $negotiation->date = $unlisted['date'];
            $negotiation->remarks = $unlisted['remarks'];

            $negotiation->save();
            $purposes = explode(' :: ', $unlisted['purpose_id']);
            foreach ($purposes as $purpose) {
                $visitpurpose = new VisitPurpose;

                $visitpurpose->user_id = $negotiation->user_id;
                $visitpurpose->visit_id = $negotiation->id;
                $visitpurpose->purpose_id = $purpose;
                $visitpurpose->date = $unlisted['date'];
                $visitpurpose->status = 'Unlisted';

                $visitpurpose->save();
            }
        }

        return response()->json(['status' => 'success', 'message' => 'Unlisted Visits have been added']);
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
