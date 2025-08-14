<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GeneralSetting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;

use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    public function __construct() {

        $this->middleware('check.license');
}
}
