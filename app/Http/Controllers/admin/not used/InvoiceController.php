<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    
    public function invoiceAdd()
    {
        return view('backend.invoice/invoiceAdd');
    }

    public function invoiceEdit()
    {
        return view('backend.invoice/invoiceEdit');
    }

    public function invoiceList()
    {
        return view('backend.invoice/invoiceList');
    }

    public function invoicePreview()
    {
        return view('backend.invoice/invoicePreview');
    }

}
