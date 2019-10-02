<?php
/**
 * Created by PhpStorm.
 * User: XiaoLin
 * Date: 2018-11-03
 * Time: 6:06 PM
 */

namespace RSSHub\Lib;

class Exception extends \Exception
{
    public function __construct($msg,$level = 'warning')
    {   
        die(XML::toRSS([
            'title' => 'RSSHub PHP Ver.',
            'link' => Config::getConfig()['general']['siteURL'],
            'description' => '[' . strtoupper($level) . '] ' . $msg,
            'items' => [],
        ]));
    }
}