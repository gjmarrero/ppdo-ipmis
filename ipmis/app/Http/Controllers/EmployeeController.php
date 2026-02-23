<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeCreateRequest;
use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $employees = Employee::all();
        return EmployeeResource::collection($employees);
    }
    public function store(EmployeeCreateRequest $request)
    {
        $data = $request->validated();
        $employee = Employee::create($data);
        return new EmployeeResource($employee);
    }

    public function update(Employee $employee, EmployeeCreateRequest $request)
    {
        $data = $request->validated();
        $employee->update($data);
        return new EmployeeResource($employee);
    }

    public function destroy(Employee $employee, Request $request){
        $employee->delete();

        return response() -> json([
            "message" => "Employee successfully deleted"
        ]);
    }
}
