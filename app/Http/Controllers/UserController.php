<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequests\DestroyRequest;
use App\Http\Requests\UserRequests\IndexRequest;
use App\Http\Requests\UserRequests\ShowRequest;
use App\Http\Requests\UserRequests\StoreRequest;
use App\Http\Requests\UserRequests\UpdateRequest;
use App\Listings\TableListing;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Http\JsonResponse
     */
    public function index(IndexRequest $request)
    {
        return TableListing::create(User::class)->processAndGet($request, [
            'id', 'email', 'name', 'surname'
        ], [
            'name', 'email', 'name', 'surname'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'surname' => $request->surname,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        if (isset($request->additionalInfo)) {
            $arr = $request->additionalInfo;
            $arr['user_id'] = $user->id;
            $user->moreInfo()->updateOrCreate([
                'user_id' => $user->id
            ], $arr);
        }

        $user->syncRoles($request->role["name"] ?? '');


        return response()->json([
            'message' => "User has been created."
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(ShowRequest $request, int $id)
    {
        try {
            return User::findOrFail($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'ERROR',
                'message' => 'User not exist'
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
    public function update(UpdateRequest $request, $id)
    {

        $user = User::findOrFail($id);
        $user->update($request->all());

        if (isset($request->additionalInfo)) {
            $arr = $request->additionalInfo;
            $arr['user_id'] = $user->id;
            $user->moreInfo()->updateOrCreate([
                'user_id' => $user->id
            ], $arr);
        }

        if (isset($request->roles)) {
            $collection = collect($request->roles);
            $collection = $collection->pluck('name');
            $user->syncRoles($collection);
        }
        // TODO: Implement role change
        return response("User has been updated", 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DestroyRequest $request, int $id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response("User has been deleted", 200);
    }
}
