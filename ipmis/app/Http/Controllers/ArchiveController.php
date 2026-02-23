<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ArchiveCreateRequest;
use App\Http\Resources\ArchiveResource;
use App\Models\Archive;
use Illuminate\Http\Request;

class ArchiveController extends Controller
{
    public function index(Request $request)
    {
        $archives = Archive::all();
        return ArchiveResource::collection($archives);
    }

    public function store(ArchiveCreateRequest $request)
    {
        $data = $request->validated();

        $request->validate([
            'file' => 'required|file',
        ]);

        if($request->hasFile('file')){
            $filePath = $request->file('file')->store('archives', 'public');
            $data['file'] = $filePath;
        }

        $archive = Archive::create($data);
        return new ArchiveResource($archive);
    }
}
