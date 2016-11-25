<?php
/**
 * Created by PhpStorm.
 * User: le.van.hai
 * Date: 11/25/2016
 * Time: 10:23 AM
 */

namespace app\Common;


class Common
{
    public static function getMaxGraph($data ){
        $maxValueArr = array();
        $initKey = array();
        if(is_array($data) && !empty($data)){
            foreach ($data as $subData){
                foreach ($subData as $key => $value){
                    $initKey[] = $key;
                }
                break;
            }
            $maxValueArr = array_reduce($data, function ($subData1, $subData2) use ($initKey){
                $result = array();
                foreach ($initKey as $key){
                    $result[$key] = @$subData1[$key] > @$subData2[$key] ? @intval($subData1[$key]) : @intval($subData2[$key]) ;
                }
                return $result;
            });
            foreach ($maxValueArr as $key => $value){
                $maxGraph = $maxValueArr[$key];
                $roundMax = round($maxGraph/5);
                if($maxGraph >= 3*$roundMax){
                    $maxGraph = 5*$roundMax + 5*ceil($roundMax/5);
                }
                $maxValueArr[$key] = intval($maxGraph);
                if($maxGraph <= 100){
                    $maxGraph = 10*(round($maxGraph/10, 0));
                    $maxGraph = $maxGraph < 5 ? 5 : $maxGraph;
                }else{
                    $length = strlen($maxGraph);
                    $maxGraphRound = 5 * pow(10, $length-2) * ceil($maxGraph/(5 * pow(10, $length -2)));
                    if($maxGraph >= 3/5*$maxGraphRound){
                        $maxValueArr[$key] = $maxGraphRound;
                        continue;
                    }
                    $splitPart = pow(10, $length -1)/2;
                    $roundMax = $splitPart * round($maxGraph / ($splitPart));
                    if($roundMax <= $maxGraph){
                        $maxGraph = $roundMax + 10*round(($maxGraph - $roundMax)/10, 0);
                    }else{
                        $maxGraph = $roundMax;
                    }
                }
                $maxValueArr[$key] = $maxGraph;
            }
        }

        return $maxValueArr;
    }

    public static function getContent($url,$postdata = false){
        if (!function_exists('curl_init')){
            return 'Sorry cURL is not installed!';
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        if ($postdata)
        {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        }
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 ;Windows NT 6.1; WOW64; AppleWebKit/537.36 ;KHTML, like Gecko; Chrome/39.0.2171.95 Safari/537.36");
        $contents = curl_exec($ch);
        $headers = curl_getinfo($ch);
        curl_close($ch);
        return array($contents, $headers);
    }

}