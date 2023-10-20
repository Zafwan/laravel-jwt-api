<?php

namespace App\Http\Controllers\Spending;

use App\Http\Controllers\Controller;
use App\Models\Year;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;

class YearController extends BaseController
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
        $years = Year::all();

        return $this->sendResponse($years, 'Years Retrieved Successfully.');
    }
}