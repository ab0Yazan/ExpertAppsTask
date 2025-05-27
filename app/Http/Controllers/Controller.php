<?php

namespace App\Http\Controllers;

use App\Helper\ApiResponse;

abstract class Controller
{
    protected static function apiResponse(): ApiResponse
    {
        return new ApiResponse();
    }
}
