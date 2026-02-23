<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ImplementationOrderCreateRequest;
use App\Http\Resources\ImplementationOrderResource;
use App\Models\Implementation;
use App\Models\ImplementationOrder;
use Illuminate\Http\Request;

class ImplementationOrderController extends Controller
{
    public function store(ImplementationOrderCreateRequest $request, $implementation_id)
    {
        $data = $request->validated();

        $implementation = Implementation::findOrFail($implementation_id);

        $order = $implementation->orders()->create($data);

        return new ImplementationOrderResource($order);
    }
}
