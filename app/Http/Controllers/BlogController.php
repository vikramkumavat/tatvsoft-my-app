<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Image;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\UnauthorizedException;

class BlogController extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [];
        if (Auth::user()->role) {
            $data['users'] = User::select('id', 'first_name', 'last_name')->where('role', '!=', '1')->get()->toArray();
        }
        return view('blog.action')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request;
        $rules = [
            'title' => ['required', 'string', 'max:250'],
            'body' => ['required', 'string', 'max:500'],
            'date' => ['required', 'string'],
            'image' => 'mimes:jpeg,jpg,png|max:10000',
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
        $data = [
            "title"         => trim($request->title),
            "body"          => trim($request->body),
            "user_id"       => $user_id,
            "start_date"    => $stDate ?? null,
            "end_date"      => $endDate ?? null,
            "active"        => $active,
        ];
        $existBlog = Blog::find($request->id);
        if (!$existBlog && $request->id) {
            return redirect()->back()->with('error', "Oops something went wrong blog doesn't exist.");
        }
        if ($existBlog) {
            $blog = Blog::where('id', '=', $request->id)->update($data);
        } else {
            $blog = Blog::create($data);
        }
        if ($blog) {
            if ($request->hasFile('image')) {
                $imageData = [
                    'url' => $uploadFile
                ];

                if (isset($request->id) && !empty($request->id)) {
                    // Delete file if exists
                    Storage::delete('/public/images/' . $existBlog->image->url);
                    $existBlog->image()->update($imageData);
                } else {
                    $blog->image()->create($imageData);
                }
            }
            return redirect()->route('dashboard')->with('success', 'Blog added');
        }
        return redirect()->back()->with('error', 'Oops something went wrong.');
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
        return view('blog.action')->with($data);
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
            Storage::delete('/public/images/' . $blog->image->url);
            return redirect()->route('dashboard')->with('success', 'Blog Deleted');
        }
        return redirect()->back()->with('error', 'Oops something went wrong.');
    }
}
