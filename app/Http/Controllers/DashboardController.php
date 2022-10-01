<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $blogs = Blog::with('user')->orderBy('created_at', 'desc')->get()->toArray();
        return view('welcome')->with(['blogs' => $blogs]);
    }
    public function dashboard()
    {

        /* I am facing issue here to combine logic that's why i use two diff syntex */
        if (Auth::user()->role) {
            $blogs = Blog::select('blogs.id', 'blogs.title', 'blogs.body', 'blogs.user_id', 'u.first_name')
                ->leftJoin('users as u', 'blogs.user_id', '=', 'u.id')
                ->orderBy('blogs.updated_at', 'desc')
                ->get()->toArray();
        } else {
            $blogs = Blog::select('blogs.id', 'blogs.title', 'blogs.body', 'blogs.user_id', 'u.first_name')
                ->leftJoin('users as u', 'blogs.user_id', '=', 'u.id')
                ->whereUserId(Auth::user()->id)
                ->orderBy('blogs.updated_at', 'desc')
                ->get()->toArray();
        }

        $normalUser = User::select('id', 'first_name', 'last_name')->where('role', '!=', '1')->get()->toArray();

        $data = [
            'users' => $normalUser,
            'blogs' => $blogs
        ];
        return view('dashboard')->with($data);
    }
}
