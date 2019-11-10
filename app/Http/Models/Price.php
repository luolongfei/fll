<?php
/**
 * 喵喵折
 *
 * @author mybsdc <mybsdc@gmail.com>
 * @date 2019/6/22
 * @time 9:17
 */

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use App\Lib\Curl;
use Illuminate\Support\Facades\Log;

class Price extends Model
{
    /**
     * 喵喵折基础URL
     */
    const APP_API_URL = 'https://www.henzanapp.com/api/v2/';

    /**
     * 获取商品最近一年历史价格
     */
    const GET_ALL_HISTORICAL_PRICE_URL = 'mmzgoods/allPriceCurve';

    /**
     * 模拟喵喵折APP
     */
    const MMZ_IOS_APP = [
        'base_uri' => self::APP_API_URL,
        'headers' => [
            'User-Agent' => 'MiaoMiaoZheApp/1.6.4 (com.miaomiaozheapp.henzan; build:1.6.4; iOS 13.1.3) Alamofire/1.6.4',
            'App-From' => 'ios',
            'Mmz-Ios-Version' => '1.6.4',
            'Php-Auth-Pw' => 'b29b3e141b99e40e2e3153e1a5a2721d',
            'Php-Auth-User' => 'mmzapp_ios',
            'Php-Ios-Client-Id' => 'ab97e6d1616f45249e9c62b2a88708ba',
            'Shu-Meng-Did' => 'D2fqNDdt6VmSRjcLoKZSl3pjKOOGD0JVEaaeRqzAZLg8YXe1',
            'Content-Type' => 'application/x-www-form-urlencoded; charset=utf-8',
        ]
    ];

    /**
     * @var array 合并必要的各个接口的返回值，用于后期组装文言
     */
    protected static $rtData = [];

    /**
     * @var string 当获取价格的某个流程出错，自定义返回文言
     */
    protected static $rtErrorMsg = '';

    /**
     * 获取最近一年所有价格
     *
     * @param array $allParams
     *
     * @return array
     * @throws \Exception
     */
    public static function getAllPrice(array $allParams)
    {
        $response = Curl::post(
            self::GET_ALL_HISTORICAL_PRICE_URL,
            [
                'zan_goods_id' => $allParams['zan_goods_id'],
                'price' => $allParams['price'],
                'url' => $allParams['url']
            ],
            self::MMZ_IOS_APP
        );
        $response = json_decode($response, true);

        if (!$response || $response['RC'] !== 1) {
            LOG::error('获取商品历史价格时出错', $response);
            throw new \Exception('获取商品历史价格出错，具体什么情况，咱也不知道，咱也没敢问。小伙子别慌，我已经在排查问题了。');
        }

        return $response['data']['pcinfo']['info'] ?? [];
    }
}
