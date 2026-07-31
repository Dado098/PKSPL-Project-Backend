<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class TestController extends Controller
{
    /**
     * Endpoint untuk menguji hak akses Administrator.
     */
    public function admin(): JsonResponse
    {
        return response()->json(['message' => 'Administrator access granted.']);
    }

    /**
     * Endpoint untuk menguji hak akses Analyst.
     */
    public function analyst(): JsonResponse
    {
        return response()->json(['message' => 'Analyst access granted.']);
    }

    /**
     * Endpoint untuk menguji hak akses Peneliti.
     */
    public function peneliti(): JsonResponse
    {
        return response()->json(['message' => 'Peneliti access granted.']);
    }

    /**
     * Endpoint untuk menguji hak akses Guest.
     */
    public function guest(): JsonResponse
    {
        return response()->json(['message' => 'Guest access granted.']);
    }
}
