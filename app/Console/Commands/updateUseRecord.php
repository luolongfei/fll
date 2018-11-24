<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DeviceDetector\DeviceDetector;
use DeviceDetector\Parser\Device\DeviceParserAbstract;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;

class UpdateUseRecord extends Command
{
    /**
     * @var Client
     */
    protected static $client;

    /**
     * @var DeviceDetector
     */
    protected static $dd;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    protected $signature = 'update:useRecord';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '通过解析客户端发送的userAgent，获取用户的设备信息。';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $batchStartTime = time();
        $lastID = 0;

        while (true) {
            if ((time() - $batchStartTime) > 1800) { // 每次循环半小时
                break;
            }

            $useRecord = DB::table('use_record')
                ->select('id', 'user_agent', 'ip')
                ->where('handle', 0)
                ->where('deleted', 0)
                ->where('id', '>', $lastID)
                ->orderBy('id', 'asc')
                ->limit(100)
                ->get()
                ->toArray();
            if (empty($useRecord)) { // 无数据则休眠半秒，防止资源占用过高，且不进行下面的处理
                usleep(500000);
                continue;
            }

            $useRecord = json_decode(json_encode($useRecord), true); // 转数组
            $lastID = last($useRecord)['id'];

            foreach ($useRecord as $record) {
                self::update($record);
            }
        }

        return true;
    }

    private static function update(array $record)
    {
        $ip = $record['ip'];
        $recordID = $record['id'];

        $dd = self::getDeviceDetector();
        $dd->setUserAgent($record['user_agent']);
        $dd->parse();
        $brand = $dd->getBrandName();
        $model = $dd->getModel();
        $os = sprintf('%s %s', $dd->getOs()['name'], $dd->getOs()['version']);
        $browser = sprintf('%s %s', $dd->getClient()['name'], $dd->getClient()['version']);

        $area = self::infoByIP($ip) ?: '';

        $time = date('Y-m-d H:i:s');
        $update = [
            'area' => $area,
            'handle' => 1,
            'handle_time' => $time,
            'brand' => $brand,
            'model' => $model,
            'os' => $os,
            'browser' => $browser,
            'updated_at' => $time
        ];

        DB::beginTransaction();
        if (DB::table('use_record')
                ->where('id', $recordID)
                ->where('deleted', 0)
                ->update($update) === false) {
            Log::error('更新id=' . $recordID . '的use_record记录时出错：', $update);
            DB::rollBack();
        }

        if ($area && DB::table('mail')
                ->where('ip', $ip)
                ->where('deleted', 0)
                ->where('handle', 0)
                ->update($update) === false) { // 顺便把mail表里的设备信息更新
            Log::error('更新ip=' . $ip . '的mail记录时出错，已回滚：' . $area);
            DB::rollBack();
        }

        DB::commit();

        return true;
    }

    private static function getDeviceDetector()
    {
        if (self::$dd === null) {
            DeviceParserAbstract::setVersionTruncation(DeviceParserAbstract::VERSION_TRUNCATION_NONE);
            self::$dd = new DeviceDetector();
        }

        return self::$dd;
    }

    private static function infoByIP($ip)
    {
        $client = self::getClient();
        $infoRes = $client->get('http://freeapi.ipip.net/' . $ip);
        $infoRes = json_decode((string)$infoRes->getBody(), true);

        sleep(1); // 免费接口有频率限制

        if ($infoRes && count($infoRes) === 5) {
            $infoRes = array_filter($infoRes);
            return implode(' - ', $infoRes);
        }

        return false;
    }

    private static function getClient()
    {
        if (self::$client === null) {
            self::$client = new Client([
                'timeout' => 30.0,
                'cookies' => true,
                'headers' => [ // 默认header，此处header中同名项可被覆盖
                    'Accept' => '*/*',
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safari/537.36',
                ],
            ]);
        }

        return self::$client;
    }
}
