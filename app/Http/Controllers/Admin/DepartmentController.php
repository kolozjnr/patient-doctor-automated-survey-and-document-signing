<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Admin\DepartmentRepository;

class DepartmentController extends Controller
{
    public function __construct(DepartmentRepository $departmentRepository)
    {
        $this->departmentRepository = $departmentRepository;
    }

    public function index()
    {
        $departments = $this->departmentRepository->allDepartments();
        return view('departments.index', compact('departments'));
    }

    public function getDepartments()
    {
        $departments = $this->departmentRepository->allDepartments();
        return response()->json([
            'success' => true,
            'departments' => $departments]);
    }

    public function edit($id)
    {
        $department = $this->departmentRepository->getDepartment($id);
        return response()->json([
            'success' => true,
            'department' => $department]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        return $this->departmentRepository->store($request->all());
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        return $this->departmentRepository->updateDepartment($id, $request->all());
    }
}
