<?php

use App\Services\FreemoPayService;
use Illuminate\Support\Facades\Route;

Route::get('/', function (FreemoPayService $freemoPayService) {
    $freemoPayService->init(env('FREEMOPAY_USER'), env('FREEMOPAY_PASSWORD'), env('FREEMOPAY_URL'));
    $res = $freemoPayService->checkPaymentStatus("36cd1aa8-55ee-4a01-920c-641b35ce381a");
    dd($res);
    return view('welcome');
});
