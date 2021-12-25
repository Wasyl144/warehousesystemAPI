<?php

namespace App\Http\Controllers\Permissions;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleRequests\DestroyRequest;
use App\Http\Requests\RoleRequests\IndexRequest;
use App\Http\Requests\RoleRequests\UpdateRequest;
use App\Http\Requests\UserRequests\ShowRequest;
use App\Listings\TableListing;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Requests\RoleRequests\StoreRequest;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(IndexRequest $request)
    {
        $result = TableListing::create(Role::class)->processAndGet($request, ['*'], ['name']);
        return $result;

    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        try {
            Role::create([
                'name' => $request->name,
                'guard_name' => 'web'
            ]);
            return response()->json([
                'message' => "Role created successfully"
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(ShowRequest $request, $id)
    {
        try {
            return response()->json(Role::findOrFail($id));
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => "Role not found."
            ], 404);
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, int $id)
    {
        try {
            $role = Role::findOrFail($id);
            $role->update([
                'name' => $request->name
            ]);
            return response()->json([
                'message' => 'Role has been edited'
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => "Role not found."
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DestroyRequest $resquest, int $id)
    {
        try {
            $role = Role::findOrFail($id);
            $role->delete();
            return response()->json([
                'message' => 'Role has been deleted'
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => "Role not found."
            ], 404);
        }
    }
}
