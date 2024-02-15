<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Category;
use function GuzzleHttp\json_encode;
use App\Http\Resources\CategoryResource;

class CategoryController extends Controller
{
    protected $repository;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Category $repository)
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
        return CategoryResource::collection($this->repository->all());
        $categories = Category::query()
        ->select('id', 'name', 'count', 'created_at', 'updated_at', 't_name')
        ->get();

    return response()->json($categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) //Store to set the createModal in vue mode functional check bottom line for update
    {
        $request->validate([
            'name' => 'required',
            't_name' => 'required',
            't_type' => 'required',
            'l_type' => 'required',
            // other validation rules...
        ]);
    
        $category = new Category();
        $category->name = $request->name;
        $category->t_name = $request->t_name; // Save t_name
        $category -> t_type = $request -> t_type;
        $category -> l_type = $request -> l_type;
        // other fields...
        $category->save();
    
        return response()->json('Category created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    { //This methode is using to make api request to load all the category in item create and update component
        return response()->json($this->repository->all());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request, $id)
    // {
    //     $this->validate($request, [
    //         'name' => ['required', 'string', 'max:255', 'unique:categories'],
    //     ]);
    //     $this->repository->findOrFail($id)->update($request->all());
    //     // return 'Updating data';
    // }
    public function update(Request $request, $id) //This one also critical lol
    {
        $request->validate([
            'name' => 'required',
            't_name' => 'required', // Add validation for the trainer name //this under update $id
            't_type' => 'required',
            'l_type' => 'required',
            // other validation rules...
        ]);

        $category = Category::find($id);
        $category->name = $request->name;
        $category->t_name = $request->t_name; // Save the trainer name
        $category -> t_type = $request -> t_type;
        $category -> l_type = $request -> l_type;
        // other fields...
        $category->save();

        return response()->json('Category updated successfully');
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

        // return ['message' => 'Category Deleted Successfully'];
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function related($id)
    {
        $posts = Category::with('item')->find($id);

        // return $posts;
        return response()->json($posts);
    }
}
