<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromView;

class UnlistedReport implements FromView
{
    /**
     * @return Collection
     */
    public function collection()
    {
        //
    }

    protected $data;

    protected $from;

    protected $to;

    public function __construct($data, $from, $to)
    {
        $this->data = $data;
        $this->from = $from;
        $this->to = $to;
    }

    public function view(): View
    {
        // dd($this->from);
        return view('dashboards.negotiations.unlisted-visit-report', [
            'list' => $this->data,
            'from' => $this->from,
            'to' => $this->to,
        ]);
    }
}
