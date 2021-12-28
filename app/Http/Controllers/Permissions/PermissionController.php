<?php

namespace App\Http\Controllers\Permissions;

use App\Http\Controllers\Controller;
use App\Http\Requests\PermissionsRequests\EditRequest;
use App\Http\Requests\RoleRequests\UpdateRequest;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json(Permission::all());
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(EditRequest $request, $id)
    {
        $role = Role::findOrFail($id);
        $permissions = Permission::all();

        return response()->json([
            'role' => $role,
            'rolePermissions' => $role->permissions,
            'allPermissions' => $permissions

        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRequest $request, $id)
    {
        $group = Role::findOrFail($id);


        if ($request->permissions != null) {
//            dd($request->permissions);
            $permissions = Permission::whereIn('name', $request->permissions)->get();
            $group->syncPermissions($permissions);
        } else {
            $group->revokePermissionTo(Permission::all());
        }

        return response()->json([
            'message' => 'Permissions updated successfully',
        ], 202);

    }

    public function self(Request $request)
    {

        return response()->json(auth()->user()->getAllPermissions()->pluck('name')->toArray(), 200);
    }
}
