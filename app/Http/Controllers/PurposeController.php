<?php

namespace App\Http\Controllers;

use App\Models\Purpose;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PurposeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $data['datas'] = Purpose::orderBy('updated_at', 'DESC')->paginate(15);

        return view('dashboards.purposes.show', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('dashboards.purposes.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'purpose' => 'required|unique:purposes',
        ]);

        Purpose::create([
            'purpose' => $request->purpose,
        ]);

        return back()->with('Status', 'New purpose added');
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
        $data['purpose'] = Purpose::findorFail($id);

        return view('dashboards.purposes.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'purpose' => 'required',
        ]);
        $request->purpose;

        $data = Purpose::findorFail($id);

        if ($data->purpose != $request->purpose) {
            if (count(Purpose::where('purpose', $request->purpose)->get()) > 0) {
                return redirect('/purpose')->with('Status', 'Duplicate Entry for purpose');
            } else {

                $data->purpose = $request->purpose;

                $data->save();

                return redirect('/purpose')->with('Status', 'purpose Updated');
            }
        }

        return redirect('/purpose')->with('Status', 'Duplicate Entry for purpose');
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
