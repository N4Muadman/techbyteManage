<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\recruitment;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class recruitmentController extends Controller
{
    public function index(){
        return view('recruitment.index');
    }

}
