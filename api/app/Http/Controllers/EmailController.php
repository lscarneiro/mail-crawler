<?php

namespace App\Http\Controllers;

use App\Email;
use Illuminate\Http\Request;

class EmailController extends Controller
{
    public function index()
    {
        $emails = Email::orderBy('id', 'desc')->take('10')->get()->pluck('email');

        return response()->json($emails);
    }
}
