<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class StaticPagesController extends Controller
{
    public function home()
    {
        $feedItems = [];
        if (Auth::check()) {
            $feedItems = Auth::user()->feed()->paginate(10); // 获取当前登录用户关注的人所有微博
        }

        return view('static_pages.home', compact('feedItems'));
    }

    public function help()
    {
        return view('static_pages.help');
    }

    public function about()
    {
        return view('static_pages.about');
    }
}
