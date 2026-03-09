<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ImplementationOrderCreateRequest;
use App\Http\Resources\ImplementationOrderResource;
use App\Models\Implementation;
use App\Models\ImplementationOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImplementationOrderController extends Controller
{
    public function store(ImplementationOrderCreateRequest $request, $implementation_id)
    {
        $data = $request->validated();

        $implementation = Implementation::findOrFail($implementation_id);

        $order = $implementation->orders()->create($data);

        if ($request->hasFile('order_file')) {
            $files = $request->file('order_file');
            $files = is_array($files) ? $files : [$files];

            foreach ($files as $file) {
                $filePath = $file->store('implementation_order_files', 'public');
                $order->files()->create([
                    'file_path' => $filePath
                ]);
            }
        }

        return new ImplementationOrderResource($order->load('files'));
    }

    public function update(ImplementationOrderCreateRequest $request, ImplementationOrder $implementation_order)
    {
        $data = $request->validated();

        $implementation_order->update($data);

        if ($request->hasFile('order_file')) {
            foreach ($implementation_order->files as $existingFile) {
                if ($existingFile->file_path && Storage::disk('public')->exists($existingFile->file_path)) {
                    Storage::disk('public')->delete($existingFile->file_path);
                }
                $existingFile->delete();
            }

            $files = $request->file('order_file');
            $files = is_array($files) ? $files : [$files];

            foreach ($files as $file) {
                $filePath = $file->store('implementation_order_files', 'public');
                $implementation_order->files()->create([
                    'file_path' => $filePath
                ]);
            }
        }

        return new ImplementationOrderResource($implementation_order->fresh()->load('files'));
    }
}
