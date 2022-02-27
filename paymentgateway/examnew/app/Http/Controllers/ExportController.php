<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends BaseController
{

    public function importExportView()
    {
       return view('import');
    }
    public function export()
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }

     public function exportUser()
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }

}
