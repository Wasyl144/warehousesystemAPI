<?php

namespace App\Http\Controllers;

use App\Http\Requests\ItemsRequests\DestroyRequest;
use App\Http\Requests\ItemsRequests\IndexRequest;
use App\Http\Requests\ItemsRequests\ShowRequest;
use App\Http\Requests\ItemsRequests\StoreRequest;
use App\Http\Requests\ItemsRequests\UpdateRequest;
use App\Listings\TableListing;
use App\Models\Item;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(IndexRequest $request)
    {
        return TableListing::create(Item::class)->processAndGet($request, ['*'], ['location', 'name']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $validated = $request->validated();
        $validated['id_category'] = $validated['category']['id'];
        Item::create($validated);

        return response()->json([
            'message' => 'Product has been added.'
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ShowRequest $request, $id)
    {
        return Item::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $id)
    {
        $item = Item::findOrFail($id);
        $validated = $request->validated();
        $validated['id_category'] = $validated['category']['id'];
        $item->update($validated);

        return response()->json([
            'message' => 'Product has been updated.'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DestroyRequest $request, $id)
    {
        $item = Item::find($id);
        $item->destroy();

        return response()->json([
            'message' => 'Product has been deleted.'
        ], 200);
    }
}
