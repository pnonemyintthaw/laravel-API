<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Resources\CategoryResource;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories=Category::all();
        return response()->json([

            'status'=>'ok',
            'totalResults'=>count($categories),
            'categories'=> CategoryResource::collection($categories),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            "name"=>"required",
            "photo"=>"required",

        ]);

        //upload file-photo
        if($request->file()) {
            // 624872374523_a.jpg
            $fileName = time().'_'.$request->photo->getClientOriginalName();

            // brandimg/624872374523_a.jpg
            $filePath = $request->file('photo')->storeAs('categoryimg', $fileName, 'public');

            $path = '/storage/'.$filePath;
        }

        //store
        $category = new Category;
        $category->name = $request->name;
        $category->photo= $path;
        $category->save();

        return new CategoryResource($category);


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
         return response()->json([

            'status'=>'ok',
            
            'category'=> new CategoryResource($category),
        ]);
         // return new CategoryResource($category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
         $request->validate([
            "name"=>"required",
            "photo"=>"sometimes|required",
            "oldphoto"=>"required"

        ]);

        //upload file-photo
        if($request->file()) {
            // 624872374523_a.jpg
            $fileName = time().'_'.$request->photo->getClientOriginalName();

            // brandimg/624872374523_a.jpg
            $filePath = $request->file('photo')->storeAs('categoryimg', $fileName, 'public');

            $path = '/storage/'.$filePath;
        }else{
            $path=$request->oldphoto;
        }


        //store
        $category =  Category::find($category);
        $category->name = $request->name;
        $category->photo= $path;
        $category->save();

         return new CategoryResource($category);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return new CategoryResource($category);
    }
}
