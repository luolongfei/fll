<?php
/**
 * 主页
 *
 * @author mybsdc <mybsdc@gmail.com>
 * @date 2018/10/13
 * @time 17:49
 */

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use App\Http\Models\Price;
use Illuminate\Support\Facades\Redis;

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

    public function copy($token = '')
    {
        $url = Redis::get($token);
        $num = explode('_', $token)[1];

        return view('home.copy', [
            'url' => $url,
            'num' => $num
        ]);
    }

    /**
     * @param Request $request
     * @param string $token
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function price(Request $request, $token = '')
    {
        if (empty($token)) {
            return view('common.error', [
                'errorMsg' => '参数缺失，无法查询，请检查并重试'
            ]);
        }

        $allData = Redis::get($token);
        if (is_null($allData)) {
            return view('common.error', [
                'errorMsg' => '啊哦，这个页面已经失效了，请重新查询并获取，每个页面的有效期为2小时'
            ]);
        }

        $allData = json_decode($allData, true);
        if (is_null($allData)) {
            return view('common.error', [
                'errorMsg' => '解码失败，小伙子别慌，我已经在处理了'
            ]);
        }

        return view('home.price', [
            'allData' => $allData
        ]);
    }
}
