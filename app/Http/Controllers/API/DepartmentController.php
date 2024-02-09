<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    // GET /api/departments
    public function index()
    {
        $departments = Department::all();
        return response()->json($departments);
    }

    // POST /api/departments
    public function store(Request $request)
    {
        $department = Department::create($request->all());
        return response()->json($department, 201);
    }

    // GET /api/departments/{department}
    public function show(Department $department)
    {
        return response()->json($department);
    }

    // PUT or PATCH /api/departments/{department}
    public function update(Request $request, Department $department)
    {
        $department->update($request->all());
        return response()->json($department);
    }

    // DELETE /api/departments/{department}
    public function destroy(Department $department)
    {
        $department->delete();
        return response()->json(null, 204);
    }
}
