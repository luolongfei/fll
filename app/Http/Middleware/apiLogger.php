<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;
use App\Lib\AppLogger;

class apiLogger
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        $logger = AppLogger::getInstance()->setLogType('api');
        $key = md5(uniqid());

        $requestContent = []; //uri，header，timestamp，ip地址
        $requestContent['uri'] = $request->url();
        $requestContent['ip'] = $request->ip();
        $requestContent['headers'] = $request->headers->all();
        $requestContent['requestData'] = $this->parsePostData($request);
        $logger->info($key . '[request]', $requestContent);

        $responseContent = $response->getContent();
        $tmp = json_decode($responseContent, true);
        $logger->info($key . '[response]', $tmp ? $tmp : [$responseContent]);

        return $response;
    }

    protected function parsePostData($request)
    {
        $encodeing = $request->header('Content-Encoding');
        switch ($encodeing) {
            case 'gzip':
                $content = $request->getContent();
                $postData = json_decode(gzdecode($content), true);
                break;
            default:
                $postData = $request->all();
        }

        return $postData;
    }
}
