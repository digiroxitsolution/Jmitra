<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sales; // Example model
use ConsoleTVs\Charts\Classes\Chartjs\Chart;

use Illuminate\Support\Facades\Storage;

class LocationController extends Controller
{
    public function showLocationForm()
    {
        return view('location');
    }

}