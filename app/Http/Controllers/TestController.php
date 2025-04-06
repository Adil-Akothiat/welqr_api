<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Utilities;

class TestController extends Controller
{
    public function test (Request $request) {
        $rndStr = new Utilities();
        return $request->fname ?? $rndStr->randomStr(10);
    }
}
