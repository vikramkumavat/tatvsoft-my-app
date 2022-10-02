<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Image;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'title' => ['required', 'string', 'max:250'],
            'body' => ['required', 'string', 'max:500'],
            'date' => ['required', 'string'],
            'image' => 'mimes:jpeg,jpg,png|required|max:10000',
        ];

        $uploadFile = '';
        if ($request->hasFile('image')) {
            $filenameOrg = $request->file('image')->getClientOriginalName();

            $filename = pathinfo($filenameOrg, PATHINFO_FILENAME);

            $extension = $request->file('image')->getClientOriginalExtension();

            $uploadFile = $filename . '_' . time() . '.' . $extension;

            $request->file('image')->storeAs('/public/images', $uploadFile);
        } else {
            $uploadFile = 'noimage.jpg';
        }

        // return $uploadFile;

        list($stDate, $endDate) = explode(' - ', $request->date);

        $stDate = date("Y-m-d", strtotime($stDate));
        $endDate = date("Y-m-d", strtotime($endDate));

        $active = isset($request->blogactive) && !empty($request->blogactive) ? 1 : 0;
        $user_id = Auth::user()->id;
        if (Auth::user()->role && !empty($request->user_id)) {
            $rules['user_id'] = ['required'];
            $user_id = $request->user_id;
        }

        $request->validate($rules);

        $blog = new Blog;
        $blog->title = trim($request->title);
        $blog->body = trim($request->body);
        $blog->user_id = $user_id;
        $blog->start_date = $stDate ?? null;
        $blog->end_date = $endDate ?? null;
        $blog->active = $active;
        if ($blog->save()) {
            if ($request->hasFile('image')) {
                $blog->image()->create([
                    'url' => $uploadFile
                ]);
            }

            return redirect()->back()->with('success', 'Blog added');
        }
        return redirect()->back()->with('error', 'Oops something went wrong.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function show(Blog $blog)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function edit(Blog $blog)
    {
        $data = [
            'blog' => $blog,
        ];
        if (!Auth::user()->role) {
            if (Auth::user()->id != $blog->user_id) {
                return redirect()->back()->with('error', 'Unauthorized request');
            }
        } else {
            $data['users'] = User::select('id', 'first_name', 'last_name')->where('role', '!=', '1')->get()->toArray();
        }
        return view('blog.edit')->with($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $rules = [
            'title' => ['required', 'string', 'max:250'],
            'body' => ['required', 'string', 'max:500'],
        ];
        $user_id = Auth::user()->id;
        if (Auth::user()->role) {
            $rules['user_id'] = ['required'];
            $user_id = $request->user_id;
        }
        $request->validate($rules);
        $data = [
            'title' => $request->title,
            'body' => $request->body,
            'user_id' => $user_id,
        ];
        $updateBlog = Blog::where('id', '=', $request->id)->update($data);
        if ($updateBlog) {
            return redirect()->route('dashboard')->with('success', 'Blog Updated');
        }
        return redirect()->back()->with('error', 'Oops something went wrong.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        $id = $request->id;
        $blog = Blog::find($id);
        if (!Auth::user()->role) {
            if (Auth::user()->id != $blog->user_id) {
                return redirect()->back()->with('error', 'Unauthorized request');
            }
        }
        if ($blog->delete()) {
            return redirect()->route('dashboard')->with('success', 'Blog Deleted');
        }
        return redirect()->back()->with('error', 'Oops something went wrong.');
    }
}
