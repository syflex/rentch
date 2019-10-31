<?php

namespace App\Http\Controllers;

use App\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Auth;

class BlogController extends Controller
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
            "user_id" => "required",
            "blog_category_id" => "required",
            "title" => "required",
            "slug" => "required",
            "is_publish" => "required"
        ]);
        if ($request->hasFile('cover_image')) 
        {
            $path = $request->file('cover_image')->store('blogs');
            $data['cover_image'] = $path;
        }
        $blog = self::store($data);
        return response()->json(['status' => 'ok', 'data'=> $blog, 'msg' => 'Data added successfully']); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public static function store($data)
    {
        try {
            $store = new Blog;
            $store->user_id = $data['user_id'];
            $store->blog_category_id = $data['blog_category_id'];
            $store->title = $data['title'];
            $store->slug = $data['slug'];
            $store->cover_image = $data['cover_image'] ?? null;
            $store->is_publish = $data['is_publish'] ?? 0;
            $store->save();
            activity()
               ->causedBy(Auth::user())
               ->performedOn($store)
               ->withProperties(['id' => $store->id])
               ->log('blog created');
            return $store; 
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public static function get()
    {
        $blogs = Blog::orderBy('created_at', 'desc')->paginate(9);
        return response()->json(['status' => 'ok', 'data' => $blogs, 'msg' => 'Data loaded successfully']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public static function get_by_limit(int $limit)
    {
        $blogs = Blog::orderBy('created_at', 'desc')->paginate($limit);
        return response()->json(['status' => 'ok', 'data' => $blogs, 'msg' => 'Data loaded successfully']);
    }
    /**
     * get a single resource.
     *
     * @param  \App\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public static function show(int $resource_id)
    {
        $blog = Blog::where('id', $resource_id)->first();
        return response()->json(['status' => 'ok', 'data' => $blog, 'msg' => 'Data loaded successfully']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public static function update(Request $request)
    {
        $validatedData = $request->validate([
            "id" => "required"
        ]);
        $data = $request->all();
        try {
            $blog = Blog::where('id', $data['id'])->first();
            if(!empty($data['title']))
            {
                $blog->title = $data['title'];
            }
            if(!empty($data['slug']))
            {
                $blog->slug = $data['slug'];
            }
            if(!empty($data['blog_category_id']))
            {
                $blog->blog_category_id = $data['blog_category_id'];
            }
            if(!empty($data['cover_image']))
            {
                $blog->cover_image = $data['cover_image'];
            }
            if(!empty($data['is_publish']))
            {
                $blog->is_publish = $data['is_publish'];
            }
            if ($request->hasFile('cover_image')) 
            {
               self::delete_image($blog->cover_image);
                $path = $request->file('cover_image')->store('blogs');
                $blog->cover_image = $path;
            }
            $blog->save();
            activity()
                   ->causedBy(Auth::user())
                   ->performedOn($blog)
                   ->withProperties(['id' => $blog->id, 'title' => $blog->title])
                   ->log('blog updated');
            return response()->json(['status'=> 'ok', 'data' => $blog, 'msg' => 'Data updated successfully']);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public static function destroy(int $blog_id)
    {
        try {
            if(Blog::where('id',$blog_id)->exists()){
                $blog = Blog::where('id', $blog_id)->first();
               self::delete_image($blog->cover_image);
                Blog::destroy($blog_id);
                activity()
                   ->causedBy(Auth::user())
                   ->performedOn($blog)
                   ->withProperties(['id' => $blog->id, 'name' => $blog->title, 'slug'=>$blog->slug])
                   ->log('blog deleted');
                return response()->json(['status' => 'ok', 'msg'=> 'Data deleted successfully']);
            }
            return response()->json(['status' => 'error', 'msg'=> 'Deleted already']);
        } catch (Exception $e) {
            
        }
    }
    /**
     * delete user image/avata
     *
     * @method upload_user_image
     * @param  \App\User  $request
     * @return \Illuminate\Http\Response
     */
    public static function delete_image(string $image_path)
    {
        activity()
                   //->causedBy($blog->user_id)
                   //->performedOn($blog)
                   ->withProperties(['cover_image' => $image_path])
                   ->log('cover image deleted');
        return Storage::delete($image_path);
    }
}
