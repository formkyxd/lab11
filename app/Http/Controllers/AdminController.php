<?php

namespace App\Http\Controllers;

abstract class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin')->only(['store', 'update', 'destroy']);
    }
}
