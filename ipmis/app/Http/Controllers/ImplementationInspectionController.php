<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ImplementationInspectionCreateRequest;
use App\Http\Resources\ImplementationInspectionResource;
use App\Models\Implementation;
use App\Models\ImplementationInspection;
use Illuminate\Http\Request;
use Storage;

class ImplementationInspectionController extends Controller
{
    public function store(ImplementationInspectionCreateRequest $request, $implementationId)
    {
        $data = $request->validated();

        $implementation = Implementation::findOrFail($implementationId);

        $inspection = $implementation->inspections()->create($data);

        if ($request->hasFile('inspection_file')) {
            $files = $request->file('inspection_file');
            $files = is_array($files) ? $files : [$files];

            foreach ($files as $file) {
                $filePath = $file->store('implementation_inspection_files', 'public');
                $inspection->files()->create([
                    'file_path' => $filePath
                ]);
            }
        }
        return new ImplementationInspectionResource($inspection->load('files'));
    }

    public function update(ImplementationInspectionCreateRequest $request, ImplementationInspection $inspection)
    {
        $data = $request->validated();

        $inspection->update($data);

        if ($request->hasFile('inspection_file')) {
            foreach ($inspection->files as $existingFile) {
                if ($existingFile->file_path && Storage::disk('public')->exists($existingFile->file_path)) {
                    Storage::disk('public')->delete($existingFile->file_path);
                }
                $existingFile->delete();
            }

            $files = $request->file('inspection_file');
            $files = is_array($files) ? $files : [$files];

            foreach ($files as $file) {
                $filePath = $file->store('implementation_inspection_files', 'public');
                $inspection->files()->create([
                    'file_path' => $filePath
                ]);
            }
        }

        return new ImplementationInspectionResource($inspection->load('files'));
    }
}
