<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\UsersImport;
use Excel;

class ImportExcelController extends Controller
{
    public function ImportExcelForm(Request $request){
        return view('import-excel');
    }
    public function ImportExcel(Request $request){
        Excel::import(new UsersImport, $request->file('excel-file'));
        return redirect('import-excel')->withMessage('Excel Imported Successfully');
    }
}
