<?php

namespace App\Http\Controllers\Admin;

use App\Exports\UsersExport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\Response;
use App\Traits\ResponseTrait;
class ExcelController extends Controller
{
    use ResponseTrait;
    public function export() 
    {   
        return Excel::download(new UsersExport, 'users.xlsx');
    }

//     public function export() 
// {   
//     $export = new UsersExport;
//     $filename = 'users.xlsx';
//     $headers = [
//         'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
//         'Content-Disposition' => 'attachment; filename="' . $filename . '"',
//     ];
//     return response()->download($export->download($filename, \Maatwebsite\Excel\Excel::XLSX), $filename, $headers);
// }
}
