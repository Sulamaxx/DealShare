<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TableController extends Controller
{
    public function tableBasic()
    {
        return view('backend.table/tableBasic');
    }

    public function tableData()
    {
        return view('backend.table/tableData');
    }

    
}
