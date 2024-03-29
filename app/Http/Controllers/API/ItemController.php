<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Item;
use function GuzzleHttp\json_encode;
use App\Http\Requests\ItemCreateRequest;
use Carbon\Carbon;
use App\Category;

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
        $request->merge(['properties' => json_encode($request->rows)]);
    
        $category = Category::find($request->input('category_id'));
        $trainingDate = Carbon::createFromFormat('Y-m-d', $request->input('training_date'));
    
        // Default to 25 years ahead for categories not matching Recert
        $expiredDate = $trainingDate->copy()->addYears(50);
    
        if ($category && $category->t_type === 'Recert') {
            $expiredDate = $trainingDate->copy()->addYear(); // 1 year ahead for Recert
        }
    
        $item = $this->repository->create(array_merge($request->all(), [
            'expired_date' => $expiredDate->format('Y-m-d'),
        ]));
    
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
        $request->merge(['properties' => json_encode($request->rows)]);
        $item = $this->repository->findOrFail($id);
    
        if ($request->has('training_date') && $request->has('category_id')) {
            $category = Category::find($request->input('category_id'));
            $trainingDate = Carbon::createFromFormat('Y-m-d', $request->input('training_date'));
    
            // Default to 25 years ahead for categories not matching Recert
            $expiredDate = $trainingDate->copy()->addYears(50);
    
            if ($category && $category->t_type === 'Recert') {
                $expiredDate = $trainingDate->copy()->addYear(); // Adjust for Recert
            }
    
            $request->merge(['expired_date' => $expiredDate->format('Y-m-d')]);
        }
    
        $item->update($request->all());
    
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
