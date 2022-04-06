<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Tips;

class TipsController extends Controller
{
    /**
     * return all instances of the Tips model
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function index() 
    {
        $tips = Tips::all();

        return response()->json($tips, 200);
    }
}
