<?php
namespace Common\Library;
class Lib_wxresponse {

    /**
     * [responseTextMsg 返回文字信息]
     * @param  [string] $fromUserName [用户openid;需要返回的用户]
     * @param  [string] $toUserName   [微信公众号]
     * @param  [string] $content      [消息内容]
     * @return [string]               [返回微信]
     */
    public function responseTextMsg($fromUserName, $toUserName, $content="") {
        $time = time();
        $textTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[%s]]></MsgType>
                    <Content><![CDATA[%s]]></Content>
                    <FuncFlag>0</FuncFlag>
                    </xml>";
        $msgType = "text";
        $resultStr = sprintf($textTpl, $fromUserName, $toUserName, $time, $msgType, $content);
        return $resultStr;
    }

    /**
     * [responseImageMsg 返回图片信息]
     * @param  [string] $fromUserName [用户openid;需要返回的用户]
     * @param  [string] $toUserName   [微信公众号]
     * @param  [string] $mediaId      [通过上传多媒体文件，得到的id]
     * @return [string]               [返回微信]
     */
    public function responseImageMsg($fromUserName, $toUserName, $mediaId="") {
        $time = time();
        $textTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[%s]]></MsgType>
                    <Image>
                        <MediaId><![CDATA[%s]]></MediaId>
                    </Image>
                    </xml>";
        $msgType = "image";
        $resultStr = sprintf($textTpl, $fromUserName, $toUserName, $time, $msgType, $mediaId);
        return $resultStr;
    }

    /**
     * [responseVoiceMsg 返回语音信息]
     * @param  [string] $fromUserName [用户openid;需要返回的用户]
     * @param  [string] $toUserName   [微信公众号]
     * @param  [string] $mediaId      [通过上传多媒体文件，得到的id]
     * @return [string]               [返回微信]
     */
    public function responseVoiceMsg($fromUserName, $toUserName, $mediaId="") {
        $time = time();
        $textTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[%s]]></MsgType>
                    <Voice>
                        <MediaId><![CDATA[%s]]></MediaId>
                    </Voice>
                    </xml>";
        $msgType = "voice";
        $resultStr = sprintf($textTpl, $fromUserName, $toUserName, $time, $msgType, $mediaId);
        return $resultStr;
    }

    /**
     * [responseVideoMsg 返回视频信息]
     * @param  [string] $fromUserName [用户openid;需要返回的用户]
     * @param  [string] $toUserName   [微信公众号]
     * @param  [string] $mediaId      [通过上传多媒体文件，得到的id]
     * @param  [string] $title        [视频消息的标题]
     * @param  [string] $description  [视频消息的描述]
     * @return [string]               [返回微信]
     */
    public function responseVideoMsg($fromUserName, $toUserName, $mediaId="", $title="", $description="") {
        $time = time();
        $textTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[%s]]></MsgType>
                    <Video>
                        <MediaId><![CDATA[%s]]></MediaId>
                        <Title><![CDATA[%s]]></Title>
                        <Description><![CDATA[%s]]></Description>
                    </Video> 
                    </xml>";
        $msgType = "video";
        $resultStr = sprintf($textTpl, $fromUserName, $toUserName, $time, $msgType, $mediaId, $title, $description);
        return $resultStr;
    }

    /**
     * [responseMusicMsg 返回音乐信息]
     * @param  [string] $fromUserName [用户openid]
     * @param  [string] $toUserName   [微信公众号]
     * @param  [string] $thumbMediaId [缩略图的媒体id，通过上传多媒体文件，得到的id]
     * @param  [string] $title        [音乐标题]
     * @param  [string] $description  [音乐描述]
     * @param  [string] $musicURL     [音乐链接]
     * @param  [string] $HQMusicUrl   [高质量音乐链接，WIFI环境优先使用该链接播放音乐]
     * @return [string]               [返回微信]
     */
    public function responseMusicMsg($fromUserName, $toUserName, $thumbMediaId="", $title="", $description="", $musicURL="", $HQMusicUrl="") {
        $time = time();
        $textTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[%s]]></MsgType>
                    <Music>
                        <Title><![CDATA[%s]]></Title>
                        <Description><![CDATA[%s]]></Description>
                        <MusicUrl><![CDATA[%s]]></MusicUrl>
                        <HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
                        <ThumbMediaId><![CDATA[%s]]></ThumbMediaId>
                    </Music>
                    </xml>";
        $msgType = "music";
        $resultStr = sprintf($textTpl, $fromUserName, $toUserName, $time, $msgType, $title, $description, $musicURL, $HQMusicUrl, $thumbMediaId);
        return $resultStr;
    }

    /**
     * [responseNewsMsg 返回图文信息]
     * @param  [string] $fromUserName   [用户openid]
     * @param  [string] $toUserName     [微信公众号]
     * @param  [array]  $articlesArr    [Array]
        * @param  [array]  $articlesArr [title]        [图文消息标题]
        * @param  [array]  $articlesArr [description]  [图文消息描述]
        * @param  [array]  $articlesArr [picurl]       [图片链接，支持JPG、PNG格式，较好的效果为大图360*200，小图200*200]
        * @param  [array]  $articlesArr [url]          [点击图文消息跳转链接]
        * @param  [array]  $articlesArr [parameter]    [点击图文消息跳转链接]
     * @return [string]                 [返回微信]
     */
    public function responseNewsMsg($fromUserName, $toUserName, $articlesArr=array()) {
        $articles = "";
        $articleCount = 0;
        $time = time();
        $textTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[%s]]></MsgType>
                    <ArticleCount>%s</ArticleCount>
                    <Articles>
                        %s
                    </Articles>
                    </xml>";
        $msgType = "news";
        foreach ($articlesArr as $key => $value) {
            if(!empty($value["parameter"])) {
                $urlInfo = parse_url($value["url"]);
                if(isset($urlInfo['query'])) {
                    $urlTemp = $value["url"] . "&userId=" . USERID . "&openId=" . $fromUserName;
                } else {
                    $urlTemp = $value["url"] . "?userId=" . USERID . "&openId=" . $fromUserName;
                }
            } else {
                $urlTemp = $value["url"];
            }
            $articles .= "<item>
                            <Title><![CDATA[" . $value["title"] . "]]></Title> 
                            <Description><![CDATA[" . $value["description"] . "]]></Description>
                            <PicUrl><![CDATA[" . $value["picurl"] . "]]></PicUrl>
                            <Url><![CDATA[" . $urlTemp . "]]></Url>
                        </item>";
            $articleCount++;
        }
        if($articleCount > 10 || $articleCount == 0) {
            $resultStr = FALSE;
        } else {
            $resultStr = sprintf($textTpl, $fromUserName, $toUserName, $time, $msgType, $articleCount, $articles);
        }
        return $resultStr;
    }

    /**
     * [responseTransferMsg 返回多客服]
     * @param  [type] $fromUserName [description]
     * @param  [type] $toUserName   [description]
     * @param  [string] $KFAccount     [description]
     * @param  [string] $HQMusicUrl   [description]
     * @return [type]               [description]
     */
    public function responseTransferMsg($fromUserName, $toUserName, $KFAccount="") {
        $time = time();
        $transInfo = "";
        $textTpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[%s]]></MsgType>%s
                    </xml>";
        $msgType = "transfer_customer_service";
        if(!empty($KfAccount)) {
            $transInfo = "
                        <TransInfo>
                           <KfAccount>![CDATA[" . $KfAccount . "]]</KfAccount>
                        </TransInfo>";
        }
        $resultStr = sprintf($textTpl, $fromUserName, $toUserName, $time, $msgType, $transInfo);
        return $resultStr;
    }
}
/* End of file lib_wxresponse.php */
/* Location: ./application/libraries/lib_wxresponse.php */