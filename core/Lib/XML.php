<?php
/**
 *Author:iLay1678
 *Date:2019-10-01
 */

namespace RSSHub\Lib;
class XML
{
    public static function toRSS($data) {
        $rss_header = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
<rss version=\"2.0\">
<channel>
            ";

        $rss_footer.= '</channel>
</rss>';

        $rss_image = '';
        if (isset($data['image'])) {
            $rss_image.= '<image>
            ';
            $data['image']['link'] = $data['image']['src'];
            unset($data['image']['src']);
            foreach ($data['image'] as $key => $val) {
                if ($key == 'title') {
                    $rss_image.= "<".$key."><![CDATA[".$val."]]></".$key.">";
                }elseif ($key == 'link') {
                    $rss_image.= "<".$key.">".str_replace('&','&#38;',$val)."</".$key.">
                    ";
                } else {
                    $rss_image.= "<".$key.">".$val."</".$key.">";

                }
            }
            $rss_image.= '</image>
            ';
            unset($data['image']);
        }
        
        $rss_item = '';
        $items = $data['items'];
        unset($data['items']);
        foreach ($items as $item) {
            if (isset($item['date'])) {
                $item['pubDate'] = date(DATE_RSS,$item['date']);
                unset($item['date']);
            }
            $rss_item.= '<item>
            ';
            foreach ($item as $key => $val) {
                if ($key == 'title' || $key == 'description') {
                    $rss_item.= "<".$key."><![CDATA[".$val."]]></".$key.">
                    ";
                }elseif ($key == 'link') {
                    $rss_item.= "<".$key.">".str_replace('&','&#38;',$val)."</".$key.">
                    ";
                } 
                else {
                    $rss_item.= "<".$key.">".$val."</".$key.">
                    ";

                }
            }
            $rss_item.= '</item>
            ';
        }
        
        $rss_top='';
         if(isset($data['date'])){
                $data['lastBuildDate']=date(DATE_RSS,$data['date']);
                unset($data['date']);
            }
        foreach ($data as $key => $val) {
            if (!is_array($val)) {
                if ($key == 'title' || $key == 'description') {
                    $rss_top.= "<".$key."><![CDATA[".$val."]]></".$key.">
                    ";
                }
                elseif ($key == 'link') {
                    $rss_top.= "<".$key.">".str_replace('&','&#38;',$val)."</".$key.">
                    ";
                } else {
                    $rss_top.= "<".$key.">".$val."</".$key.">
                    ";

                }
            }

        }



        //xxx.xml文件尾部
        return $rss_header.$rss_top.$rss_image.$rss_item.$rss_footer;
    }
}