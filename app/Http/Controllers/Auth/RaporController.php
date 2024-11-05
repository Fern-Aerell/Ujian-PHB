<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RaporController extends Controller
{
    public function index() {
        return inertia('Auth/Rapor');
    }
}
