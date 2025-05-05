<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LegalDatabaseController extends Controller
{
    public function index()
    {
        return view('admin.legal_database.index');
    }
}
