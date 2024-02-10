<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Item;
use function GuzzleHttp\json_encode;
use App\Http\Requests\ItemCreateRequest;
use Carbon\Carbon;

class ItemController extends Controller
{

    protected $repository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Item $repository)
    {
        $this->repository = $repository;
        // $this->middleware('auth:api');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->repository->with('category')->get();
        // return Item::paginate(20);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    { 

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ItemCreateRequest $request)
    {
        // Decode the rows and merge them into the request attributes
        $request->merge(['properties' => json_encode($request->rows)]);

        // Calculate the expired date based on the training date
        $trainingDate = $request->input('training_date');
        // $departments = $request->input('departments');
        $expiredDate = \Carbon\Carbon::createFromFormat('Y-m-d', $trainingDate)
                                    ->addDays(60)
                                    ->format('Y-m-d');

        // Create the item with the request data and calculated expired date
        $item = $this->repository->create(array_merge($request->all(), [
            'expired_date' => $expiredDate,
            'departments' => $request->departments,
        ]));

        // Return the created item or a response
        return response()->json(['item' => $item], 201);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->repository->with('category')->findOrFail($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return $this->repository->findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ItemCreateRequest $request, $id)
    {
        // Decode the rows and merge them into the request attributes
        $request->merge(['properties' => json_encode($request->rows)]);
    
        // Find the item
        $item = $this->repository->findOrFail($id);
    
        // Calculate the expired date based on the training date, if it's changed
        if ($request->has('training_date')) {
            $trainingDate = $request->input('training_date');
            $expiredDate = \Carbon\Carbon::createFromFormat('Y-m-d', $trainingDate)
                                           ->addDays(60)
                                           ->format('Y-m-d');
            $request->merge(['expired_date' => $expiredDate]);
        }
    
        // Update the item with the request data
        $item->update($request->all());
    
        // Return the updated item or a response
        return response()->json(['item' => $item], 200);
    }
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->repository->findOrFail($id)->delete();
    }
}
