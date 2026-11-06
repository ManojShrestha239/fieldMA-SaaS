<?php

namespace App\Http\Controllers;

use App\Models\Ageing;
use App\Models\Area;
use App\Traits\CustomNepaliCalendarConverter;
use App\Traits\Nepali_Calendar;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class AccountController extends Controller
{
    use CustomNepaliCalendarConverter,Nepali_Calendar;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $data['areas'] = Area::get();

        return view('dashboards.excelupload', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $data['areas'] = Area::get();

        return view('dashboards.excelupload', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            'area' => 'required|unique:ageings,id',
            'excel_file' => 'required|file|mimes:xlsx',
        ]);
        $file = $request->file('excel_file');

        $today = $this->custom_AD_to_BS(date('Y-m-d'));

        $originalname = $file->getClientOriginalName();
        $filename = rand().'-'.$originalname;

        $ageing = Ageing::where('area_code', $request['area'])->first();

        if (! $ageing) {
            $content = File::get($file);
            $upload = Storage::disk('public')->put('ledger/'.$filename, $content);
            if ($upload) {
                Ageing::create([
                    'upload_date' => $today,
                    'area_code' => $request['area'],
                    'report' => $filename,
                ]);

                return redirect('/uploadfile/create')->with('Status', 'File Uploaded');
            } else {
                return redirect('/uploadfile/create')->with('Status', 'File not Uploaded');
            }
        } else {
            if (Storage::disk('public')->exists('ledger/'.$ageing->report)) {

                Storage::disk('public')->delete('ledger/'.$ageing->report);
            }
            $content = File::get($file);
            $upload = Storage::disk('public')->put('ledger/'.$filename, $content);
            if ($upload) {
                $new = Ageing::find($ageing->id);

                $new->upload_date = $today;
                $new->report = $filename;
                $new->save();

                return redirect('/uploadfile/create')->with('Status', 'File Uploaded');
            } else {
                return redirect('/uploadfile/create')->with('Status', 'File not Uploaded');
            }
        }

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

    public function artisan()
    {
        Artisan::call('migrate');
        // Artisan::call('db:seed');
        Artisan::call('storage:link');
        Artisan::call('optimize');
        Artisan::call('config:clear');

        return 'OK';
    }
}
