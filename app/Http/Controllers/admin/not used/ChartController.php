<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChartController extends Controller
{

    public function columnChart()
    {
        return view('backend.chart/columnChart');
    }

    public function lineChart()
    {
        return view('backend.chart/lineChart');
    }

    public function pieChart()
    {
        return view('backend.chart/pieChart');
    }

}
