<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Message;
use carbon\carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Pusher\Pusher;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $id = Auth::user();
        $my_id = $id->id;
        $admin_id = 0;

        Message::where(['from' => $my_id, 'to' => $admin_id, 'is_read' => 0])->update(['is_read' => 1]);
        $messages = Message::where(function ($query) use ($admin_id, $my_id) {
            $query->where('from', $my_id)->where('to', $admin_id);
        })->orWhere(function ($query) use ($admin_id, $my_id) {
            $query->where('from', $admin_id)->where('to', $my_id);
        })->get();

        return response()->json(['message' => $messages]);
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
            'message' => 'required',
        ]);
        if ($valid->fails()) {
            return response()->json(['status' => 'success', 'message' => $valid->errors()], 422);
        }
        $to = 0;
        $id = Auth::user();
        $from = $id->id;

        $data = new Message;

        $data->from = $from;
        $data->to = $to;
        $data->message = $request->input('message');
        $data->is_read = 0;
        // $data->created_at = carbon::now();
        $data->save();
        // return $data;

        $options = [
            'cluster' => 'ap2',
            'useTLS' => true,
        ];

        $pusher = new Pusher(
            config('app.PUSHER_APP_KEY'),
            config('app.PUSHER_APP_SECRET'),
            config('app.PUSHER_APP_ID'),
            $options
        );

        $data = ['from' => $from, 'to' => $to]; // sending from and to user id when pressed enter
        $pusher->trigger('my-channel', 'my-event', $data);
        // return $data;

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
