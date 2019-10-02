<?php
/**
 * Created by PhpStorm.
 * User: XiaoLin
 * Date: 2018-11-03
 * Time: 5:55 PM
 */

require_once __DIR__ . '/autoload.php';
error_reporting(0);

/*
 * Parse Query String Start
 */

$query = explode('/',$_GET['s']);
header('Content-Type: text/xml; charset=utf-8');
header('Access-Control-Allow-Origin: *');
if (isset($query[1]) && isset(RH_ROUTES[$query[1]])) //TODO: Change `is_array` to `isset` because of PHPStorm's bug.
{
    $group = RH_ROUTES[$query[1]];
    $count = 0;

    foreach ($group as $route => $info) {
        $count = 2;
        while ($count <= (count($query) - 1)) {
            $str = '';
            for ($i = 2;$i <= $count;$i++)
                $str .= $query[$i] . '/';
            $str = substr($str,0,-1);

            if (!is_bool(stripos($route,$str)))
                if ($route == $str) {
                $data = $group[$route];
                if ((count($query) - $count) > 0) {
                    for ($i = 0;$i < count($data['param']);
                        $i++) {
                        $data['data'][$data['param'][$i]] = $query[$i + $count + 1];
                    }
                }
                break 2;
            } else
                $count++;
            else
                break;
        }
    }
}

if (isset($data)) {
    if (count($data['param']) > count($data['data']))
        die(\RSSHub\Lib\XML::toRSS([
        'title' => 'RSSHub Error',
        'description' => '[' . strtoupper("ERROR") . '] ' . '参数错误',
        'link' => \RSSHub\Lib\Config::getConfig()['general']['siteURL'],
        'items' => [],
    ]));

    define('RH_ROUTE',$data);
    try
    {
        $result = call_user_func_array($data['handler'],$data['data']);
    } catch (Exception $exception) {
        die(\RSSHub\Lib\XML::toRSS([
            'title' => 'RSSHub Error',
            'description' => '[' . strtoupper("ERROR") . '] ' . $exception->getTraceAsString(),
            'link' => \RSSHub\Lib\Config::getConfig()['general']['siteURL'],
            'items' => [],
        ]));
    }

    if (isset($_GET['filter'])) {
        $result = \RSSHub\Lib\Query::filter($result,$_GET['filter']);
    }
    if (isset($_GET['filter_title'])) {
        $result = \RSSHub\Lib\Query::title($result,$_GET['filter_title']);
    }
    if (isset($_GET['filter_description'])) {
        $result = \RSSHub\Lib\Query::description($result,$_GET['filter_description']);
    }
    if (isset($_GET['filterout'])) {
        $result = \RSSHub\Lib\Query::filterout($result,$_GET['filterout']);
    }
    if (isset($_GET['filterout_title'])) {
        $result = \RSSHub\Lib\Query::out_title($result,$_GET['filterout_title']);
    }
    if (isset($_GET['filterout_description'])) {
        $result = \RSSHub\Lib\Query::out_description($result,$_GET['filterout_description']);
    }
    if (isset($_GET['regular'])) {
        $result = \RSSHub\Lib\Query::regular($result,$_GET['regular']);
    }if (isset($_GET['limit'])) {
        $result['items'] = array_slice($result['items'],0,$_GET['limit']);
    }if (isset($_GET['tgiv'])) {
        $result = \RSSHub\Lib\Query::tgiv($result,$_GET['tgiv']);
    }

    echo \RSSHub\Lib\XML::toRSS($result);
} else {

    if (strpos($_GET['s'],'api/routes') !== false) {
        header('Content-Type: text/json; charset=utf-8');
    }
    $url = "https://rsshub.app".$_SERVER['REQUEST_URI'];
    echo file_get_contents($url);
    /*die(\RSSHub\Lib\XML::toRSS([
        'title' => 'RSSHub Error',
        'description' => '[' . strtoupper("ERROR") . '] ' . '路由错误',
        'link' => \RSSHub\Lib\Config::getConfig()['general']['siteURL'],
        'items' => [],
    ]));*/
}
/*
 * Parse Query String Start
 */
