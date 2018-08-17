<?php

namespace App\Http\Controllers;

use Auth;

class AbstractBaseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
}
