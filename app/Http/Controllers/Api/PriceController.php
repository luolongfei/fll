<?php
/**
 * 历史价格
 * @author mybsdc <mybsdc@gmail.com>
 * @date 2018/10/14
 * @time 11:05
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Jenssegers\Agent\Agent;

class PriceController extends Controller
{
    /**
     * 喵喵折 pc api
     */
    const MMZ_PC_API = 'https://ext.henzanapp.com/api.html';

    /**
     * 喵喵折 app api
     */
    const MMZ_APP_API = 'https://www.henzanapp.com/api/v2/';
    const MMZ_GET_STANDARD_URL = 'mmztemplate/getStandardUrl'; // 换取标准URL
    const MMZ_GOODS_INFO = 'mmzgoods/goodsInfo'; // 获取商品详情
    const MMZ_PRICE_INFO = 'mmzgoods/bottomAreaInfo'; // 历史价格

    private static $timeout = 30.0;

    /**
     * 通过喵喵折PC接口获取历史价格
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getByPc(Request $request)
    {
        try {
            $productUrl = $request->post('productUrl', '');
            if (empty($productUrl)) throw new \Exception('输入的商品网址为空');
            if (stripos($productUrl, 'http') === false) throw new \Exception('请输入一个合法的商品网址');

            self::handleUri($productUrl, $shopName);

            $agent = new Agent();
            DB::table('use_record')->insert([
                'product_name' => '',
                'url' => $productUrl,
                'shop_name' => $shopName,
                'ip' => $request->ip(),
                'device' => $agent->device(),
                'os' => $agent->platform(),
                'os_version' => $agent->version($agent->platform()),
                'browser' => $agent->browser(),
                'created_at' => date('Y-m-d H:i:s')
            ]);

            $client = new Client([
                'timeout' => self::$timeout,
                'cookies' => true
            ]);
            $response = $client->post(self::MMZ_PC_API, [
                'query' => [
                    'tplmd5' => '-8829710027106315300',
                    'path1' => 'qihoo-mall-goodsinfo',
                    'path2' => 'goodspricecmp',
                    'prevpop' => '',
                    'url' => $productUrl,
                    'v' => 'v5',
                    'bfrom' => 'normal',
                    'cv' => '6.0.1.2',
                    'hisOpn' => '0',
                    'toolbar_state' => 'open',
                    'isGulike' => 'false',
                    'mid' => '',
                    'tPrice' => '',
                    'tSale' => '',
                    'fromTp' => '0',
                    'ref' => $productUrl,
                ],
                'headers' => [
                    'Accept' => 'application/json, text/javascript, */*; q=0.01',
                    'Origin' => 'chrome-extension://ekbmhggedfdlajiikminikhcjffbleac',
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.67 Safari/537.36',
                    'Accept-Language' => 'zh-CN,zh;q=0.9,ja;q=0.8,en;q=0.7',
                    'Cookie' => 'mmzdd=701e99c83d18ed99ae5a5ae7dcbd1b36',
                ],
                'curl' => [
                    CURLOPT_POSTFIELDS => 'checkinfo=c9f8d7a8a8d7fb8c2c68b8ab09d8e8cbb8096a98b809a8c8db19d8e9a8d788bbbb8809abeb09e9a8d719f8b8d8e8e9a8d7e8881988b819b8b8e898e9a8d71988b8e9a8d7f809f819cce9a8d7bceb2c7b8888090d888809bce9a8d7a8986ce9a8d75c7cbb68fb5c1c1ddcab88e8b868a898fb5c1ce9a8d7e9a8d799b8d78ccccc0c88a8d7fb8c2c68e8b8f8f8dbeb9b986a88b809a8c8db19d8e9a8d7a8cba8a8f8a8f8b8e9a8d7b8c88888a8e9a8d71909b8f8b8c809b8e898e9a8d709e9a8d709d81919cce9a8d7bceb2c7b8888090d888809bce9a8d7a8986ce9a8d75c7cbb68fb5c1c1ddcab88e8b868a898fb5c1ce9a8d7e9a8d799b8d78ccccc0c88a8d7fb8c2c68f8f8d8e8dbd8ebcb6a88b809a8c8db19d8e9a8d78819bb9b19b8eba8e9a8d788a8b898b8e9a8d788a80998190909c8e898e9a8d78819a8e9a8d719b8e88898cce9a8d7bceb2c7b8888090d888809bce9a8d7a8986ce9a8d75c7cbb68fb5c1c1ddcab88e8b868a898fb5c1ce9a8d7e9a8d799b8d78ccccc0c88a8d7fb8c2c68a8ebabb8b8ab9be86ab8a809a8c8db19d8e9a8d7cb09a8cb9bbb9bd8e9a8d719c809a8d8e9a8d7f8f888d8c809a809e8b8e9a8d7a819a8e9a8d7a8a8a8f8cce9a8d7bceb2c7b8888090d888809bce9a8d7a8986ce9a8d75c7cbb68fb5c1c1ddcab88e8b868a898fb5c1ce9a8d7e9a8d799b8d78ccccc0c88a8d7fb8c2c6898c8bbe8d89bcb196a88b809a8c8db19d8e9a8d7a88819c8db9bd8c8e9a8d719f8e8d8e8e9a8d79819c8b88819b898c8b8e9a8d7c8d898e9a8d7f819a8f8cce9a8d7bceb2c7b8888090d888809bce9a8d7a8986ce9a8d75c7cbb68fb5c1c1ddcab88e8b868a898fb5c1ce9a8d7e9a8d799b8d78ccccc0c88a8d7a8a8d799b8d7a8a8d7fb5c1c1ccc4cdc5ca8a8d7b9a8d7a8a8d7a9f9e8d8a8b9f8d7b9f8d7b9f8d70909d71909d7f8d9d78809d7b9a9d7d8d9d7b999d7d809d7d8d9d7b9f8d7b9f8d7b9f8d7a8a9d71909d709d9d7e8a9d7b819d719d9d7a8a8d799b8d7a8a8d77ceb6c1cdc3cbca8a8d7b9a8d7a8a8d718d809d7e809d7d8d9d7fb3c88a80819a9d7c8a9d709d9d78819d7e9a9d709d9d78809d79899d7f8d9d799a9d7e919d7d8d9d7c909d7d809d7d8d9d7b809d7d809d7d8d9d71919a8a8d799b8d7a8a8d7cc0cfb1cdbaceba8a8d7b9a8d7a8a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d76cb9d8d7d9b8d7ec1ccbe9a8d7b9b8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d76cb9d8d7d9b8d79be9a8d7b9b8d7d9b8d7a8a8d7b9d8d73c6c1c4c58fcdb1ceca8a8d7b9d8d7c9b8d7bcbc9b4cbb88a8d7a8a8d7b9d8d7db6c7c6cb8a8d7a8a8d7b9d8d7c9b8d7ebdbac0c88a8d79bb9b8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d76cb9d8d7d9b8d76c9b8cbce9a8d7b9b8d78809d7c819d719d9d7b809d7e9a9d7c8d9d78809d7b9a9d7d8d9d7d819d798a9d7d8d9d7d9b8d7a8a8d7b9d8d75cdcbc585c7cac8ca8a8d7b9d8d7c9b8d7bcbc9b4cbb88a8d76c9b8cbcb9b8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d76cb9d8d7d9b8d7a8a8d7b9d8d7dbcb1c0ca8a8d7b9d8d7c9b8d73c7c7c0c589bcc9bcb88a8d7a8a8d7b9d8d7bc6c7c1ccc7c5c7cac8c584c4c9b58fcdb1cec88a8d7dbac7c5c585c7cac8c582aa8a8d7b9d8d7c9b8d7bcbc9b4cbb88a8d7ec1ccbb9b8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d76cb9d8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d76cb9d8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d76cb9d8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d76cb9d8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d76cb9d8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d76cb9d8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d76cb9d8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d76cb9d8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d76cb9d8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d76cb9d8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d788a8d76cb9d8d7a8a8d799b8d7a8a8d75c7cac8ca8a8d7b9a8d7a8a8d7a9a8d7f809d709a9d7c8d9d79819a8a8d799b8d7a8a8d75cdc6ccc5cbba8a8d7b9a8d7a8a8d7f819d799a9d7d8d9d7d899d78819d709d9d7b819d709a9d7c8d9d79809d7b819d7d8d9d7f899d799a9d7c8d9d7db4c8c8c99b919d709a9d7c8d9d7b999d799a9d7c8d9d7a8a8d799b8d7a8a8d7db5c9b6ccc6c9b0cbbacdb5ca8a8d7b9a8d7a8a8d7a9f9e8d8a8a8a8d799b8d7a8a8d71dcc1cbb9b8c9bbb7b3cbc1ccba8a8d7b9a8d7a8a8d70b88a8d7db6c7c0c8a1cdb4c8c8c99a8a8d799b8d7a8a8d7db5c9b6c7bccbbdccb7cac8ca8a8d7b9a8d7a8a8d70b88a8d7db6c7c0c8a1ca8a8d799b8d7a8a8d74cdbcb7c5c7bccbbdccb7cac8ca8a8d7b9a8d7a8a8d7c8a9d719a9d7d8d9d7f89888a8a8a8d799b8d7a8a8d7db5c1ccc7bcbdbcbcb9ba8a8d7b9a8d7a8a8d7f8b8a8190988d8a8a8d799b8d7a8a8d7acdbab5cdc6c7bccbbdccb7cac8ca8a8d7b9a8d7a8a8d7db4c8c8c99a8a8d799b8d7a8a8d7cb6c9bacaba8a8d7b9a8d7a8a8d7d8d8e8b9a8d7b8d8e8b9a8d7f8091919a8a8d799b8d7a8a8d7cb1ccc9bbba8a8d7b9a8d7a8a8d7b999d799a9d7c8d9d7f819d7b909d7d8d9d7a8a8d799b8d7a8a8d7cc7c8cdbcba8a8d7b9a8d7a8a8d799a9d7b919d7e8d9d7a909d71909d7e8d9d7b9f8d7b9f8d7b9f8d7e999d7d999d709d9d79919d78809d719d9d799a9d7b919d7e8d9d7a909d71909d7e8d9d7b9f8d7b9f8d7b9f8d799a9d7b919d7e8d9d7a909d71909d7e8d9d7a8a8d799b8d7a8a8d7bcab5cdcacbba8a8d7b9a8d7a8a8d7a8a8d799b8d7a8a8d7ac9bec7bdc3cbca8a8d7b9a8d78899b8d7a8a8d7db4c9bba7c6ca8a8d7b9a8d7a8a8d7886898a8a8d799b8d7a8a8d76c7c1cbcacdbeca8a8d7b9a8d7a8a8d7a8a8d799b8d7a8a8d7ccbb1cacccbc1ccba8a8d7b9a8d79899b8d7a8a8d7db4c9bbabc1ca8a8d7b9a8d7a8a8d7fb8c2c6898c8bbe8d89bcb196a88b809a8c8db19d8e9a8d7a88819c8db9bd8c8e9a8d719f8e8d8e8e9a8d79819c8b88819b898c8b8e9a8d7c8d898e9a8d7f819a8f8cce9a8d7bceb2c7b88d8c80d88d8c8bce9a8d7986ce9a8d75c7cbb68fb5c1c1ddcab88e8b868a898fb5c1ce9a8d7e9a8d799b8d78ccccc0ca8a8d799b8d7a8a8d7bb1c8ca8a8d7b9a8d7a8a8d799a9d7b919d7e8d9d7a909d71909d7e8d9d7f9c89899d7e9a9d7c8d9d7d8a9d7c819d7f8d9d79919d78809d719d9d7c819d79809d709d9d70999d79909d7d8d9d7a9a9d7f899d7f8d9d788a8d7a8a9d71909d709d9d7e8a9d7b819d719d9d788a8d7a9f9e8d8a888a8d718d8e80998990888a8d70b88a8d7db6c7c0c8a1c88a8d7db4c8c8c99a8a8d799b8d7a8a8d7db5c9b6ca8a8d7b9a8d71919a80999b8d7a8a8d7dbbb1cac8ca8a8d7b9a8d7b899b8d7a8a8d7cb1a8c7c0cbca8a8d7b9a8d7a8a8d7b919d709a9d7c8d9d7b999d799a9d7c8d9d7a8a8d799b8d7a8a8d7db5c9b6a8c7c0cbca8a8d7a9f8d7'
                ]
            ]);

            $rt = json_decode((string)$response->getBody(), true);
            if (isset($rt['pcinfo']['info']) && count($rt['pcinfo']['info'])) {
                return response()->json([
                    'status' => 0,
                    'chart_data' => [
                        'price_info' => $rt['pcinfo']['info'],
                        'begin_date' => $rt['pcinfo']['bd'],
                        'end_date' => $rt['pcinfo']['ed'],
                        'lowest_price' => $rt['pcinfo']['lpr'],
                        'highest_price' => $rt['pcinfo']['hpr'],
                    ],
                    'message_array' => [
                        [
                            'message' => '数据取得成功'
                        ],
                    ],
                    'system_date' => date('Y-m-d H:i:s')
                ]);
            }

            throw new \Exception('获取数据失败');
        } catch (\Exception $e) {
            return response()->json([
                'status' => 9,
                'chart_data' => [],
                'message_array' => [
                    [
                        'message' => $e->getMessage()
                    ],
                ],
                'system_date' => date('Y-m-d H:i:s')
            ]);
        }
    }

    private static function handleUri(&$uri, &$shopName)
    {
        if (stripos($uri, 'jd.com')) {
            $shopName = '京东';
            $uri = explode('?', $uri)[0];
        } else if (stripos($uri, 'tmall.com')) {
            $shopName = '天猫';
            $uri = explode('?', $uri)[0];
        } else if (stripos($uri, 'taobao.com')) {
            $shopName = '淘宝';
            $uri = explode('?', $uri)[0];
        } else if (stripos($uri, 'amazon.cn') || stripos($uri, 'amazon.com')) {
            $shopName = '亚马逊';
            $uri = explode('?', $uri)[0];
        } else if (stripos($uri, 'dangdang.com')) {
            $shopName = '当当网';
            $uri = explode('?', $uri)[0];
        } else {
            $shopName = '未知';
        }
    }

    /**
     * 通过喵喵折APP接口获取历史价格
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getByApp(Request $request)
    {
        try {
            $qq = $request->post('qq', '未知');
            $productUrl = $request->post('productUrl', '');
            if (empty($productUrl)) throw new \Exception('输入的商品网址为空');
            if (stripos($productUrl, 'http') === false) throw new \Exception('商品网址非法，再检查一下吧');

            $client = new Client([
                'timeout' => self::$timeout,
                'cookies' => true,
                'base_uri' => self::MMZ_APP_API,
                'headers' => [ // 默认header，此处header中同名项可被覆盖
                    'Accept' => '*/*',
                    'Accept-Language' => 'zh-Hans-CN;q=1, ja-JP;q=0.9',
                    'User-Agent' => 'MiaoMiaoZheApp/1.4.5 (iPhone; iOS 12.1; Scale/3.00)',
                    'App-From' => 'ios',
                    'Mmz-Ios-Version' => '1.4.5',
                    'Php-Auth-Pw' => 'b29b3e141b99e40e2e3153e1a5a2721d',
                    'Php-Auth-User' => 'mmzapp_ios',
                    'Php-Ios-Client-Id' => 'ab97e6d1616f45249e9c62b2a88708ba',
                ],
            ]);

            /**
             * 首先，获取商品标准的URI
             */
            $standardUriRes = $client->post(self::MMZ_GET_STANDARD_URL, [
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ],
                'form_params' => [
                    'query' => $productUrl,
                ]
            ]);
            $standardUriRes = json_decode((string)$standardUriRes->getBody(), true);
            if (!$standardUriRes || $standardUriRes['RC'] !== 1 || $standardUriRes['data']['status'] !== true) {
                throw new \Exception('获取商品标准URI出错');
            }
            $url = $standardUriRes['data']['url_info']['url'];
            $goodsId = $standardUriRes['data']['url_info']['goods_id'];
            $shop = $standardUriRes['data']['url_info']['shop'];

            /**
             * 其次，获取商品实时信息
             */
            $goodsInfoRes = $client->get(self::MMZ_GOODS_INFO, [
                'query' => [
                    'id' => $goodsId,
                    'type' => $shop,
                ],
            ]);
            $goodsInfoRes = json_decode((string)$goodsInfoRes->getBody(), true);
            if (!$goodsInfoRes || $goodsInfoRes['RC'] !== 1) {
                throw new \Exception('获取商品实时信息出错');
            }
            $zanId = isset($goodsInfoRes['data']['zan_goods']['id']) ? $goodsInfoRes['data']['zan_goods']['id'] : 0; // 此项不是所有商品都有
            $summary = isset($goodsInfoRes['data']['zan_goods']['summary']) ? $goodsInfoRes['data']['zan_goods']['summary'] : '没有描述';
            $title = $goodsInfoRes['data']['goods_info']['title'];
            $image = $goodsInfoRes['data']['goods_info']['multi_pic'][0];
            $price = $goodsInfoRes['data']['goods_info']['price'];
            $merchantName = $goodsInfoRes['data']['goods_info']['merchant_name'];
            $shopName = $goodsInfoRes['data']['goods_info']['shop_name'];
            $cateName = $goodsInfoRes['data']['goods_info']['cate_name'];
            $sellCount = $goodsInfoRes['data']['goods_info']['sell_count'];
            $saleMessage = $goodsInfoRes['data']['goods_info']['sale_message'];

            /**
             * 最后，获取商品历史价格
             */
            $queryOpt = [
                'url' => $url,
                'price' => $price,
            ];
            if ($zanId) {
                $queryOpt['zan_goods_id'] = $zanId;
            }
            $priceInfoRes = $client->get(self::MMZ_PRICE_INFO, [
                'query' => $queryOpt,
            ]);
            $priceInfoRes = json_decode((string)$priceInfoRes->getBody(), true);
            if (!$priceInfoRes || $priceInfoRes['RC'] !== 1) {
                throw new \Exception('获取商品历史价格出错');
            }
            $priceInfo = $priceInfoRes['data']['pcinfo'];

            /**
             * 存个档
             */
            $agent = new Agent();
            DB::table('use_record')->insert([
                'product_name' => $title,
                'product_image' => $image,
                'merchant_name' => $merchantName, // 店名
                'cate_name' => $cateName,
                'sell_count' => $sellCount,
                'sale_message' => $saleMessage,
                'url' => $url,
                'shop_name' => $shopName,
                'ip' => $request->ip(),
                'qq' => $qq,
                'device' => $agent->device(),
                'os' => $agent->platform(),
                'os_version' => $agent->version($agent->platform()),
                'browser' => $agent->browser(),
                'created_at' => date('Y-m-d H:i:s')
            ]);

            return response()->json([
                'status' => 0,
                'price_data' => $priceInfo,
                'summary' => $summary,
                'title' => $title,
                'image' => $image,
                'merchantName' => $merchantName,
                'shopName' => $shopName,
                'sellCount' => $sellCount,
                'saleMessage' => $saleMessage,
                'message_array' => [
                    [
                        'message' => ''
                    ],
                ],
                'system_date' => date('Y-m-d H:i:s')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 9,
                'price_data' => [],
                'message_array' => [
                    [
                        'message' => $e->getMessage()
                    ],
                ],
                'system_date' => date('Y-m-d H:i:s')
            ]);
        }
    }
}