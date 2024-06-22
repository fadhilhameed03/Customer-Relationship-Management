<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = User::all();
        return response()->json([
            'status' => 200,
            'customers' => $customers,
        ]);
    }
}
