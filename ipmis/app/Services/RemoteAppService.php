<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class RemoteAppService
{
    public function getPublicData()
    {
        $response = Http::get('http://192.168.6.221/project_plans?');

        if ($response->successful()) {
            return $response->json();
        }

        return null;
    }
}