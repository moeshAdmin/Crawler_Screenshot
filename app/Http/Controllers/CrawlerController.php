<?php

namespace App\Http\Controllers;
use DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Crawler\Crawler;
use Facebook\WebDriver\WebDriverKeys;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Firefox\FirefoxOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\WebDriverBy;
date_default_timezone_set("Asia/Taipei");

class CrawlerController extends Controller
{
    function index()
    {
        return view('crawler.index');
    }

    public function startUrlCrawler(Request $request)
    {   
        /*Crawler::create()
        ->setCrawlObserver(<class that extends \Spatie\Crawler\CrawlObservers\CrawlObserver>)
        ->startCrawling($url);*/
        //$user = DB::table('crawler_data')->where('title', 'tttt')->first();
        $dir = $this->basicRun($request->url);
        if($dir){
            $retData['code'] = 200;
            $retData['data']['token'] = $request->_token;
            $retData['data']['dir'] = $dir;
            $retData['data']['url'] = $request->url;
        }else{
            $retData['code'] = 404;
            $retData['data']['token'] = $request->_token;
            $retData['data']['dir'] = $dir;
            $retData['data']['url'] = $request->url;
        }
        
        $json = json_encode($retData);
        return $json;
    }

    public function startBackground(Request $request)
    {
        $dir = $this->fullRun($request->url,$request->dir);
        $retData['code'] = 200;
        $retData['data'] = null;
        $json = json_encode($retData);
        return $json;
    }

    public function saveScreenshot(Request $request)
    {
        $dir = $request->dir;
        $imgBase64 = $request->imgBase64;
        //$imgBase64 = explode(',', $request->imgBase64)[1];
        //file_put_contents('cache/'.$dir.'/screenshot.png', base64_decode($imgBase64));
        if(strlen($imgBase64)<5){
            $retData['code'] = 901;
            $retData['imgBase64'] = $imgBase64;
            $json = json_encode($retData);
            return $json;
        }
        $retData['code'] = 200;
        $retData['imgBase64'] = $imgBase64;
        $affected = DB::table('crawler_data')
              ->where('dir', $dir)
              ->update(['screenshot' =>$imgBase64]);
        $json = json_encode($retData);
        return $json;
    }

    public function getPastUrlData(Request $request)
    {
        if((int)$request->page>0){
            $offset = (int)$request->page*5;
        }else{
            $offset = 0;
        }
        $where = [];

        if($request->filter_title){
            array_push($where,['title', 'like','%'.$request->filter_title.'%']);
        }
        if($request->filter_desc){
            array_push($where,['desc', 'like','%'.$request->filter_desc.'%']);
        }
        if($request->filter_at){
            array_push($where,['created_at', 'like','%'.$request->filter_at.'%']);
        }
        $count = DB::table('crawler_data')->where($where)->count();
        $resData = DB::table('crawler_data')
                    ->orderBy('created_at', 'desc')
                    ->where($where)
                    ->skip($offset)
                    ->take(5)
                    ->get()->toArray();
                    
        $pastData = [];
        foreach ($resData as $key => $value) {
            $pastData[$key]['title'] = $value->title;
            $pastData[$key]['desc'] = $value->desc;
            $pastData[$key]['dir'] = $value->dir;
            $pastData[$key]['created_at'] = $value->created_at;
            $pastData[$key]['url'] = $value->url;
            $pastData[$key]['status'] = $value->status;
            $pastData[$key]['screenshot'] = $value->screenshot;
        }
        $retData['code'] = 200;
        $retData['data']['pastData'] = $pastData;
        $retData['data']['page']['total_page'] = (int)ceil($count/5);
        $retData['data']['page']['offset'] = (int)$request->offset;
        $json = json_encode($retData);
        return $json;
    }

    public function getUrlDetail(Request $request)
    {
        $dir = $request->dir;
        $resData = DB::table('crawler_data')
                    ->where('dir', $dir)
                    ->get()->toArray();
        foreach ($resData as $key => $value) {
            $detailData[$key]['title'] = $value->title;
            $detailData[$key]['desc'] = $value->desc;
            $detailData[$key]['dir'] = $value->dir;
            $detailData[$key]['created_at'] = $value->created_at;
            $detailData[$key]['url'] = $value->url;
            $detailData[$key]['status'] = $value->status;
            $detailData[$key]['screenshot'] = $value->screenshot;
            $detailData[$key]['html'] = file_get_contents('cache/'.$dir.'/cache.html');
        }
        $retData['code'] = 200;
        $retData['data']['detailData'] = $detailData;
        $json = json_encode($retData);
        return $json;
    }

    private function basicRun($url)
    {
        $dir = date('YmdHis').rand(100,999).'/'.base64_encode(urlencode($url));

        $result = $this->getCurl($url,null);
        
        if(!$result){
            DB::table('crawler_data')->insert(
                array('title' => $url, 'desc' => '','url'=>$url,'dir'=>$dir,'created_at'=>date('Y-m-d H:i:s'),'screenshot'=>'none','status'=>'Error')
            );
            return false;
        }
        DB::table('crawler_data')->insert(
            array('title' => $result['title'], 'desc' => $result['desc'],'url'=>$url,'dir'=>$dir,'created_at'=>date('Y-m-d H:i:s'),'screenshot'=>'none','status'=>'Queued')
        );
        return $dir;
    }

    private function fullRun($url,$dir)
    {
        //$url = 'https://www.pchome.com.tw/';
        //$dir = date('YmdHis').'/'.base64_encode(urlencode($url));
        $result = $this->getCurl($url,null)['html'];
        $result = $this->aynsHtml($url,$dir,$result);
        $str = '
            <html>
            <head>
            <meta charset="utf-8">
            <script src="http://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
            <script type="text/javascript">   
                window.onload = function() {
                  setTimeout(function(){
                        screenshot();
                    },5000);
                };
                function screenshot(){
                    html2canvas(document.body).then(function(canvas) {
                        document.body.appendChild(canvas);
                        var a = document.createElement("a");
                        imgbase64 = canvas.toDataURL("image/jpeg");
                        //console.log(url,"'.$dir.'");
                        document.querySelector("#iframe-pic").value = imgbase64;
                        //a.href = canvas.toDataURL("image/jpeg").replace("image/jpeg", "image/octet-stream");
                        //a.download = "image.jpg";
                        //a.click();
                    });
                }
            </script> 
            </head>
        ';
        $result = $str.'
            <body>
            <input id="iframe-dir" type="hidden" value="'.$dir.'">
            <input id="iframe-pic" type="hidden" value="">
            <div id="btnSave" class="btn btn-danger" style="display:none;z-index: 999;background: #fff;position: fixed;top: 0;" onclick="screenshot()">Download screenshot</div>'.str_replace(array('<body','</body>','<head','</head>'), array('<div','</div>','<div','</div>'), $result).'</body></html>

        ';
        $this->saveData($dir,'cache.html','w+',$result);
        $affected = DB::table('crawler_data')
              ->where('dir', $dir)
              ->update(['status' => 'Finished']);
        
        return 'cache/'.$dir.'/cache.html';
    }

    private function getCurl($url,$postData,$header=null,$ckfile=null)
    {
        $ch = curl_init();
        $header[0] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.110 Safari/537.36';
        $header[1] = 'Referer: '.$url;
        curl_setopt($ch, CURLOPT_URL, $url);       
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $ckfile);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $ckfile);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $output = curl_exec($ch);
        if(curl_errno($ch) != 0){
            //echo curl_errno($ch).":".str_replace("'","",curl_error($ch)).$url;
            return false;
        }
        curl_close($ch);

        preg_match_all('/<title(.*)>(.*)<\/title>/', $output, $title);
        preg_match_all('/<meta name="description" content="(.*?)">/', $output, $desc);


        $retData['html'] = $output;   
        $retData['title'] = '';
        $retData['desc'] = '';
        if(isset($title[2][0])){
            $retData['title'] = html_entity_decode(htmlspecialchars_decode(strip_tags($title[2][0])));
        }
        if(isset($desc[1][0])){
            $retData['desc'] = str_replace('/', '', html_entity_decode(htmlspecialchars_decode(strip_tags($desc[1][0]))));
        }
        
        return $retData;
    }

    private function saveData($filePos,$fileName,$writeType,$content)
    {
        $dir = 'cache/'.$filePos;
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }
        $fp = fopen($dir.'/'.$fileName,$writeType);
        if(!$fp){
        }else{
            fwrite($fp,$content);
            fclose($fp);
        }
    }

    private function aynsHtml($url,$dir,$html)
    {

        preg_match_all('/src=(?:\'|")(.*?)(?:\'|")/i', $html, $temp);
        preg_match_all('/(?:href)=(?:\'|")(.*?css.*?)(?:\'|")/i', $html, $temp2);
        $result = array_merge($temp[1],$temp2[1]);
        $total = count($result);
        foreach ($result as $key => $elText) {
            //echo $elURL.PHP_EOL;
            if(strlen($elText)<2){continue;}
            //if($key>10){continue;}
            $getElURL = $elText;
            if(!preg_match('/^http/', $elText)&&$elText){
                $getElURL = $url.'/'.$elText;
            }
            $result = $this->getCurl($getElURL,null)['html'];
            if(!$result){continue;}
            
            $fileName = date('YmdHis').rand(100000,999999);
            $this->saveData($dir,$fileName,'w+',$result);
            $this->saveData($dir,'process','w+',($key+1).'/'.$total);
            $html = str_replace($elText, $fileName, $html);
        }
        $this->saveData($dir,'process','w+','Finished');
        return $html;
    }

}
