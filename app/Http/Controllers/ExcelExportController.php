<?php

namespace App\Http\Controllers;

use App\Exports\ProductExport;
use Maatwebsite\Excel\Facades\Excel;

class ExcelExportController extends Controller
{
    public function export()
    {
        return Excel::download(new ProductExport, 'Final-product.xlsx');
    }
}
