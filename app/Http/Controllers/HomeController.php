<?php
/**
 * 主页
 * @author mybsdc <mybsdc@gmail.com>
 * @date 2018/10/13
 * @time 17:49
 */

namespace App\Http\Controllers;


class HomeController extends Controller
{
    public function index()
    {
        return view('home.index');
    }

    public function about()
    {
        return view('home.about');
    }
}