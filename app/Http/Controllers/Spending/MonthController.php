<?php

namespace App\Http\Controllers\Spending;

use App\Http\Controllers\Controller;
use App\Models\Month;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;

class MonthController extends BaseController
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        $months = Month::all();

        return $this->sendResponse($months, 'Months Retrieved Successfully.');
    }
}