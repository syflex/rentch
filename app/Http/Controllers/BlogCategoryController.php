<?php

namespace App\Http\Controllers;

use App\BlogCategory;
use Illuminate\Http\Request;

class BlogCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $data = $request->all();
        $validatedData = $request->validate([
            "name" => "required"
        ]);
        $blog_cat = self::store($data);
        return response()->json(['status' => 'ok', 'data'=> $blog_cat, 'msg' => 'Data added successfully']); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($data)
    {
        try {
            $blog_category = new BlogCategory;
            $blog_category->name = $data['name'];
            $blog_category->description = $data['description'];
            $blog_category->save();
            activity()
                   //->causedBy($blog->user_id)
                   ->performedOn($blog_category)
                   ->withProperties(['id' => $blog_category->id, 'name' => $blog_category->name])
                   ->log('blog_category created');
            return $blog_category; 
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\BlogCategory  $blogCategory
     * @return \Illuminate\Http\Response
     */
    public function get()
    {
        $blog_category = BlogCategory::all();
       
        return response()->json(['status' => 'ok', 'data' => $blog_category, 'msg' => 'Data loaded successfully']);
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\BlogCategory  $blogCategory
     * @return \Illuminate\Http\Response
     */
    public function show(int $resource_id)
    {
        $blog_category = BlogCategory::find($resource_id);
       
        return response()->json(['status' => 'ok', 'data' => $blog_category, 'msg' => 'Data loaded successfully']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\BlogCategory  $blogCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(BlogCategory $blogCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\BlogCategory  $blogCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BlogCategory $blogCategory)
    {
        $validatedData = $request->validate([
            "id" => "required",
            "name" => "required"
        ]);
        $data = $request->all();
        try {
            $blog_category = BlogCategory::where('id', $data['id'])->first();
            if(!empty($data['name']))
            {
                $blog_category->name = $data['name'];
            }
            if(!empty($data['description']))
            {
                $blog_category->description = $data['description'];
            }
            $blog_category->save();
            activity()
                   //->causedBy($data['user_id'])
                   ->performedOn($blog_category)
                   ->withProperties(['id' => $blog_category->id, 'title' => $blog_category->title])
                   ->log('blog_category updated');
            return response()->json(['status'=> 'ok', 'data' => $blog_category, 'msg' => 'Data updated successfully']);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\BlogCategory  $blogCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $resource_id)
    {
        try {
            if(BlogCategory::where('id',$resource_id)->exists()){
                $blog_category = BlogCategory::where('id', $resource_id)->first();
                BlogCategory::destroy($resource_id);
                activity()
                   //->causedBy($blog_category->user_id)
                   ->performedOn($blog_category)
                   ->withProperties(['id' => $blog_category->id, 'name' => $blog_category->name])
                   ->log('blog_category deleted');
                return response()->json(['status' => 'ok', 'msg'=> 'Data deleted successfully']);
            }
            return response()->json(['status' => 'error', 'msg'=> 'Deleted already']);
        } catch (Exception $e) {
            
        }
    }
}
