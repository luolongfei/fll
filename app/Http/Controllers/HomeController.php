<?php
/**
 * 主页
 *
 * @author mybsdc <mybsdc@gmail.com>
 * @date 2018/10/13
 * @time 17:49
 */

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;

class HomeController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('home.index');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function about()
    {
        return view('home.about');
    }

    /**
     * @param Request $request
     * @param string $urlCode
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function price(Request $request, $urlCode = '')
    {
        if (empty($urlCode)) {

        }

        return view('home.price');
    }
}
