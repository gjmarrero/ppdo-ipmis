<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Implementation;
use App\Models\ImplementationMonthlyAccomplishment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ImplementationMonthlyAccomplishmentController extends Controller
{
    public function batchUpdate(Request $request)
    {
        $data = $request->all();

        foreach ($data as $item) {
            $accomplishment = ImplementationMonthlyAccomplishment::find($item['id']);

            if ($accomplishment) {
                $accomplishment->actual = $item['actual'] ?? $accomplishment->actual;
                $accomplishment->date_actual_submitted = now();
                $accomplishment->save();
            }
        }

        return response()->json([
            'message' => 'Accomplishments updated successfully',
        ]);
    }
}
