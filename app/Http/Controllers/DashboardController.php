<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $blogs = Blog::with(['user', 'image'])->orderBy('created_at', 'desc');
        $blogs->active()->notexpired();
        $blogs = $blogs->get()->toArray();
        return view('welcome')->with(['blogs' => $blogs]);
    }
    public function dashboard()
    {

        /* I am facing issue here to combine logic that's why i use two diff syntex */
        $blogs = Blog::with('image')->select('blogs.id', 'blogs.title', 'blogs.body', 'blogs.user_id', 'blogs.active', 'u.first_name')->leftJoin('users as u', 'blogs.user_id', '=', 'u.id');
        if (!Auth::user()->role) {
            $blogs->where('user_id', '=', Auth::user()->id);
            $blogs->active()->notexpired();
        }
        $blogs = $blogs->orderBy('blogs.updated_at', 'desc')
            ->get()->toArray();

        $normalUser = User::select('id', 'first_name', 'last_name')->where('role', '!=', '1')->get()->toArray();

        $data = [
            'users' => $normalUser,
            'blogs' => $blogs
        ];
        return view('dashboard')->with($data);
    }
}
