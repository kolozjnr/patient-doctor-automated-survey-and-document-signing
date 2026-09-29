<?php

namespace App\Repositories\Admin;

use App\Models\Department;
use Prettus\Repository\Eloquent\BaseRepository;

class DepartmentRepository extends BaseRepository
{
    public function model()
    {
        return Department::class;
    }
    
 public function allDepartments()
{
    return $this->model->latest()->get();
}


    public function store(array $data)
    {
        return $this->model->create($data);
    }
    public function updateDepartment($id, array $data)
    {
        $department = $this->model->findOrFail($id);
        $department->update($data);
        return $department;
    }

    public function getDepartment($id)
    {
        return $this->model->findOrFail($id);
    }
}