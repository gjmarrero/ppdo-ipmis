<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;

class BIPTISController extends Controller
{
    public function index()
    {
        try {
            $response = Http::timeout(5)->get('http://192.168.6.224/project_plans');

            return response()->json([
                'message' => 'Fetched successfully',
                'data' => $response->json(),
            ]);
        } catch (RequestException $e) {
            return response()->json([
                'error' => 'Request exception',
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
            ], 500);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Unexpected exception',
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ], 500);
        }

        // $response = Http::get('http://192.168.6.224/project_plans');

        // if ($response->successful()) {
        //     return response()->json([
        //         'message' => 'Fetched successfully',
        //         'data' => $response->json()
        //     ]);
        // }

        // return response()->json([
        //     'error' => 'Request failed',
        //     'status' => $response->status(),
        //     'body' => $response->body(),
        // ]);
    }
}
