<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;



class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         try {
            $filters = request()->all('title','author_name','active');

           $blogs = Blog::filter(request()->only('title','author_name','created_at','active'))
                ->ordering(request()->only('fieldName','shortBy'))
                ->orderBy('id','desc')
                ->paginate(request()->perPage ?? $this->per_page)->withQueryString();

            return Inertia::render('Admin/blog/BlogList',compact('blogs','filters'));
            } catch (\Throwable $e) {
                Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
                return back()->with('error','Server Error');
            }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            return Inertia::render('Admin/blog/BlogCreateEdit');
        } catch (\Throwable $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error','Server Error');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'author_name' => 'required',
            // 'blog_created_date' => 'required|date',
            'blog_image' => 'required|mimes:jpeg,jpg,png,gif|max:50000',
            'content' => 'required',
            'active' => 'required',
        ],
        [
            'active.required'    => 'The status field is required',
        ]);


        $blog = new Blog;

        if($request->file('blog_image')){

            $request->validate([
                'blog_image' => 'required|mimes:jpeg,jpg,png,gif|max:50000',
            ]);
            $blog->blog_image = $request->file('blog_image')->store('blog_image');
        }
        if($request->file('author_image')){
            $request->validate([
                'author_image' => 'required|mimes:jpeg,jpg,png,gif|max:50000',
            ]);
            $blog->author_image = $request->file('author_image')->store('author_image');
        }

        $blog->title = $request->title;
        $blog->author_name = $request->author_name;
        // $blog->blog_created_date = $request->blog_created_date;
        $blog->content = $request->content;
        $blog->active = $request->active;
        $blog->save();

        session()->flash('success', 'Blog created successfully');
        return redirect()->route('admin.blog.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $blog = Blog::find($id);
            return Inertia::render('Admin/blog/BlogCreateEdit',compact('blog'));

        } catch (\Throwable $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error','Server Error');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // dd($request->all());
        $request->validate([
            'title' => 'required',
            'author_name' => 'required',
            // 'blog_created_date' => 'required|date',
            'content' => 'required',
            'active' => 'required',
        ],
        [
            'active.required'    => 'The status field is required',
        ]);


        $blog = Blog::find($id);

        if($blog)
        {

            if($request->file('blog_image')){

                $request->validate([
                    'blog_image' => 'required|mimes:jpeg,jpg,png,gif|max:50000',
                ]);
                $blog->blog_image = $request->file('blog_image')->store('blog_image');
            }
            if($request->file('author_image')){
                $request->validate([
                    'author_image' => 'required|mimes:jpeg,jpg,png,gif|max:50000',
                ]);
                $blog->author_image = $request->file('author_image')->store('author_image');
            }

            $blog->title = $request->title;
            $blog->author_name = $request->author_name;
            // $blog->blog_created_date = $request->blog_created_date;
            $blog->content = $request->content;
            $blog->active = $request->active;
            $blog->save();

            session()->flash('success', 'Blog has been updated successfully');
            return redirect()->route('admin.blog.index');

        }else{
            session()->flash('error', 'Something went wrong');
            return redirect()->route('admin.blog.index');
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $blog = Blog::where('id',$id)->first();
            if($blog)
            {
                $blog->delete();

                if($blog->blog_image)
                {
                    File::delete(storage_path('app/'.$blog->blog_image));
                }
                if($blog->author_image)
                {
                    File::delete(storage_path('app/'.$blog->author_image));
                }

                session()->flash('success', 'Blog deleted successfully');
                return back();
            }

        } catch (\Throwable $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error','Server Error');
        }
    }

    public function changeBlogStatus(Blog $blog)
    {
        try {
            $blog->active = ($blog->active == 1) ? 0 : 1;
            $blog->save();
            session()->flash('success', 'Blog status successfully changed');
            return back();

        } catch (\Throwable $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
           return back()->with('error','Server Error');
        }
    }
}
