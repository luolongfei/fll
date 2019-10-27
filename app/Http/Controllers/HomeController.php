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

    /**
     * @param Request $request
     * @param string $urlCode
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function price(Request $request, $urlCode = '')
    {
        if (empty($urlCode)) {
            return view('common.error', [
                'errorMsg' => 'URL CODE 参数缺失，无法查询，请检查并重试'
            ]);
        }

        $uri = json_decode($urlCode, true);
        if (!$uri) {
            return view('common.error', [
                'errorMsg' => 'URL CODE 解码失败，请检查并重试'
            ]);
        }

        try {
            $pi = Price::getPriceText($uri);
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return view('common.error', [
                'errorMsg' => $e->getMessage()
            ]);
        }

        return view('home.price');
    }
}
