<?php
/**
 * 邮件
 * @author mybsdc <mybsdc@gmail.com>
 * @date 2018/11/8
 * @time 20:00
 */

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Support\Facades\DB;
use Jenssegers\Agent\Agent;

class Mail
{
    public function idea(Request $request)
    {
        $qq = $request->post('qq', '');
        $content = $request->post('content', '');

        try {
            $agent = new Agent();
            DB::table('mail')->insert([
                'content' => $content,
                'qq' => $qq,
                'ip' => $request->ip(),
                'device' => $agent->device(),
                'os' => $agent->platform(),
                'os_version' => $agent->version($agent->platform()),
                'browser' => $agent->browser(),
                'created_at' => date('Y-m-d H:i:s')
            ]);

            self::sendMail(
                sprintf('陛下，QQ为%s的用户上奏了', $qq),
                [
                    $qq,
                    $content
                ],
                null,
                'idea',
                strpos($qq, '@') === false ? $qq . '@qq.com' : $qq
            );
        } catch (\Exception $e) {
            return response()->json([
                'status' => 9,
                'message_array' => [
                    [
                        'message' => $e->getMessage()
                    ],
                ],
                'system_date' => date('Y-m-d H:i:s')
            ]);
        }

        return response()->json([
            'status' => 0,
            'message_array' => [
                [
                    'message' => '发送成功'
                ],
            ],
            'system_date' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * 发送邮件
     * @param string $subject 标题
     * @param string|array $content 正文
     * @param string $to 收件人，选传
     * @param string $template 模板，选传
     * @param string $replyTo 接收回复的地址
     * @throws \Exception
     */
    public static function sendMail($subject, $content, $to = '', $template = '', $replyTo = '')
    {
        $mail = new PHPMailer(true);

        // 邮件服务配置
        $mail->SMTPDebug = 0; // debug，正式环境应关闭 0：关闭 1：客户端信息 2：客户端和服务端信息
        $mail->isSMTP(); // 告诉PHPMailer使用SMTP
        $mail->Host = config('mail.host'); // SMTP服务器
        $mail->SMTPAuth = true; // 启用SMTP身份验证
        $mail->Username = config('mail.username'); // 账号
        $mail->Password = config('mail.password'); // 密码
        $mail->SMTPSecure = config('mail.encryption'); // 将加密系统设置为使用 - ssl（不建议使用）或tls
        $mail->Port = config('mail.port'); // 设置SMTP端口号 - tsl使用587端口，ssl使用465端口
        $mail->CharSet = 'UTF-8'; // 防止中文邮件乱码
        $mail->setLanguage('zh_cn', base_path('vendor') . '/phpmailer/phpmailer/language/'); // 设置语言
        $mail->setFrom(config('mail.from.address'), config('mail.from.name')); // 发件人
        $mail->addAddress($to ?: '593198779@qq.com', '罗叔叔'); // 添加收件人，参数2选填
        $mail->addReplyTo($replyTo ?: '593198779@qq.com', '留言者'); // 收到的回复的邮件将被发到此地址

        /**
         * 抄送和密送都是添加收件人，抄送方式下，被抄送者知道除被密送者外的所有的收件人，密送方式下，
         * 被密送者知道所有的被抄送者，但不知道其它的被密送者。
         * 抄送好比@，密送好比私信。
         */
//        $mail->addCC('cc@example.com'); // 抄送
//        $mail->addBCC('bcc@example.com'); // 密送
        // 添加附件，参数2选填
//        $mail->addAttachment('README.md', '说明.txt');

        // 内容
        $mail->Subject = $subject; // 标题
        /**
         * 正文
         * 使用html文件内容作为正文，其中的图片将被base64编码，另确保html样式为内联形式，且某些样式可能需要!important方能正常显示，
         * msgHTML方法的第二个参数指定html内容中图片的路径，在转换时会拼接html中图片的相对路径得到完整的路径，最右侧无需“/”，PHPMailer
         * 源码里有加。css中的背景图片不会被转换，这是PHPMailer已知问题，建议外链。
         * 此处也可替换为：
         * $mail->isHTML(true); // 设为html格式
         * $mail->Body = '正文'; // 支持html
         * $mail->AltBody = 'This is an HTML-only message. To view it, activate HTML in your email application.'; // 纯文本消息正文。不支持html预览的邮件客户端将显示此预览消息，其它情况将显示正常的body
         */
        $template = file_get_contents(base_path('resources/views/mail/') . ($template ?: 'default') . '.html');
        if (is_array($content)) {
            array_unshift($content, $template);
            $message = call_user_func_array('sprintf', $content);
        } else if (is_string($content)) {
            $message = sprintf($template, $content);
        } else {
            throw new \Exception('邮件内容只支持字符串或一维数组');
        }
        $mail->msgHTML($message, base_path('public/mail/'));

        if (!$mail->send()) throw new \Exception($mail->ErrorInfo);
    }
}