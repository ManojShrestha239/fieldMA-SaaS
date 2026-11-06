<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromView;

class UnlistedDetailedReport implements FromView
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
        // dd($this->from);
        return view('dashboards.negotiations.unlisted-visit-detailed-report', [
            'list' => $this->data,
            'from' => $this->from,
            'to' => $this->to,
            'user' => $this->user,
        ]);
    }
}
