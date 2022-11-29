<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        return response([
            'client_count'  => User::count(),
            'company_count' => Company::count(),
        ], 200);
    }
}
