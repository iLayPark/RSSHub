<?php
/**
 *Author:iLay1678
 *Date:2019-10-01
 */

namespace RSSHub\Lib;
class Query
{
    public static function filter($result,$filter) {
        $items = $result['items'];
        unset($result['items']);
        foreach ($items as $item) {
            if (!preg_match("/$filter/",$item['title']) && !preg_match("/$filter/",$item['description'])) {
                unset($item);
            }
            $result['items'][] = $item;
            $result['items'] = array_filter($result['items']);
        }
        return $result;
    }
    public static function title($result,$filter_title) {
        $items = $result['items'];
        unset($result['items']);
        foreach ($items as $item) {
            if (!preg_match("/$filter_title/",$item['title'])) {
                unset($item);
            }
            $result['items'][] = $item;
            $result['items'] = array_filter($result['items']);
        }
        return $result;
    }
    public static function description($result,$filter_description) {
        $items = $result['items'];
        unset($result['items']);
        foreach ($items as $item) {
            if (!preg_match("/$filter_description/",$item['description'])) {
                unset($item);
            }
            $result['items'][] = $item;
            $result['items'] = array_filter($result['items']);
        }
        return $result;
    }
    public static function filterout($result,$filterout) {
        $items = $result['items'];
        unset($result['items']);
        foreach ($items as $item) {
            if (preg_match("/$filterout/",$item['title']) || preg_match("/$filterout/",$item['description'])) {
                unset($item);
            }
            $result['items'][] = $item;
            $result['items'] = array_filter($result['items']);
        }
        return $result;
    }
    public static function out_title($result,$filterout_title) {
        $items = $result['items'];
        unset($result['items']);
        foreach ($items as $item) {
            if (preg_match("/$filterout_title/",$item['title'])) {
                unset($item);
            }
            $result['items'][] = $item;
            $result['items'] = array_filter($result['items']);
        }
        return $result;
    }
    public static function out_description($result,$filterout_description) {
        $items = $result['items'];
        unset($result['items']);
        foreach ($items as $item) {
            if (preg_match("/$filterout_description/",$item['description'])) {
                unset($item);
            }
            $result['items'][] = $item;
            $result['items'] = array_filter($result['items']);
        }
        return $result;
    }
    public static function regular($result,$regular) {
        $items = $result['items'];
        unset($result['items']);
        foreach ($items as $item) {
            preg_match_all("/$regular/is",$item['description'],$description);
            $description = implode('',$description[1]);
            unset($item['description']);
            $item['description'] = $description;
            $result['items'][] = $item;
            $result['items'] = array_filter($result['items']);
        }
        return $result;
    }
    public static function tgiv($result,$tgiv) {
        $items = $result['items'];
        unset($result['items']);
        foreach ($items as $item) {
            $item['link'] = 'https://t.me/iv?url='.urlencode($item['link']).'&rhash='.$tgiv;
            $result['items'][] = $item;
            $result['items'] = array_filter($result['items']);
        }
        return $result;
    }

}