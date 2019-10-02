<?php namespace RSSHub\Plugins\blog;
use Curl\Curl;
use RSSHub\Lib\Cache;
use RSSHub\Lib\Exception;
use RSSHub\Lib\XML;
use Symfony\Component\DomCrawler\Crawler;
class cross
{
    public $_info = [
        'routes' => [
            'cross/:url' => 'cross',
        ],
    ];

    public static function cross($url) {

        if ($url == null) {
            throw new Exception('url不能为空','error');
        }
        $url='http://'.$url.'/cross.html';
        $md5 = md5($url);
        $data = Cache::getCache($md5,function () use ($url) {
            $curl = new Curl();
            $curl->setReferrer($url);
            $curl->setOpt(CURLOPT_SSL_VERIFYPEER,0);
            $curl->setOpt(CURLOPT_SSL_VERIFYHOST,0);
            $curl->setopt(CURLOPT_FOLLOWLOCATION,1);
            $data = $curl->get($url);
            return $data;
        });

        $request=json_decode($data,true);


        $data = [];
        $crawler = new Crawler();
        $crawler->addHtmlContent($request);
        try {

            $data['title'] = $crawler->filterXPath('//span[contains(@class,"text-black")]')->text().'的时光机';
            $data['description'] = $crawler->filterXPath('//div[contains(@class, "m-b m-t-sm")]/small')->text();
            $data['link'] = $url;
            $data['date'] = filemtime(__DIR__ . '/../../cache/' . $md5 . '.json');
            $crawler->filterXPath('//ol[contains(@class, "comment-list")]/div/div')->each(function(Crawler $node, $i) use (&$data) {
                $items = [
                    'title' => $node->filterXPath('//div/span[contains(@datetime, "")][2]')->text(),
                    'link' => $data['link'],
                    'date' => strtotime($node->filterXPath('//div/span[contains(@datetime, "")][2]')->attr('datetime')),
                    'description' => $node->filterXPath('//div[contains(@class, "panel-body")]')->html(),
                ];
                $data['items'][] = $items;
            });
            return $data;
        } catch (\Exception $e) {
            throw new Exception("获取数据失败，请检查参数是否正确，并确保网站启用了时光机，且时光机页面为cross.html,如果网站做了防护，可能会爬取失败");
        }

    }
}