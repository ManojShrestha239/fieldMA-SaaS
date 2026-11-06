<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class VisitDetailedReport implements FromView
{
    /**
     * @return \Illuminate\Contracts\View\View;
     */
    protected $data;

    protected $from;

    protected $to;

    protected $user;

    public function __construct($data, $from, $to, $user)
    {
        $this->data = $data;
        $this->from = $from;
        $this->to = $to;
        $this->user = $user;
    }

    public function view(): View
    {
        // dd($this->data);
        return view('dashboards.visit-reports.visit-excel-report', [
            'list' => $this->data,
            'from' => $this->from,
            'to' => $this->to,
            'user' => $this->user,
        ]);
    }
}
