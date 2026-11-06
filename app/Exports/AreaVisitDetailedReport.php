<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromView;

class AreaVisitDetailedReport implements FromView
{
    /**
     * @return Collection
     */
    public function collection()
    {
        //
    }

    protected $data;

    protected $area;

    protected $from;

    protected $to;

    public function __construct($data, $area, $from, $to)
    {
        $this->data = $data;
        $this->area = $area;
        $this->from = $from;
        $this->to = $to;
    }

    public function view(): View
    {
        return view('dashboards.areavisit-reports.area-visit-detailed-report', [
            'list' => $this->data,
            'area' => $this->area,
            'from' => $this->from,
            'to' => $this->to,
        ]);
    }
}
