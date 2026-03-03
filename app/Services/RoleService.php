<?php

namespace App\Services;

use App\Repositories\BaseRepository; // Kita pakai base karena sederhana
use App\Models\Role;

class RoleService extends BaseService
{
    protected $roleRepo;

    public function __construct(Role $role)
    {
        // Langsung inject model jika tidak buat repository khusus
        $this->roleRepo = new BaseRepository($role);
    }

    public function getRoles(array $filters = [])
    {
        $roles = $this->roleRepo->getAll($filters, $filters['limit'] ?? 10);
        return $this->response(true, 'Roles loaded', $roles);
    }

    public function createRole(array $data)
    {
        $this->authorizeRole('admin');
        $role = $this->roleRepo->create($data);
        return $this->response(true, 'Role created', $role, 201);
    }

    public function updateRole($id, array $data)
    {
        $this->authorizeRole('admin');
        if ($this->roleRepo->update($id, $data)) {
            return $this->response(true, 'Role updated', $this->roleRepo->find($id));
        }
        return $this->response(false, 'Role not found', null, 404);
    }

    public function deleteRole($id)
    {
        $this->authorizeRole('admin');
        if ($this->roleRepo->delete($id)) {
            return $this->response(true, 'Role deleted');
        }
        return $this->response(false, 'Role not found', null, 404);
    }
}
