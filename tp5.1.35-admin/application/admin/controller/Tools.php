<?php

namespace app\admin\controller;
use think\Config;
use think\Controller;


class Tools extends Common
{


    protected function  _initialize()
    {
        parent::_initialize();

    }

    public function upload()
    {
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");

        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            exit; // finish preflight CORS requests here
        }
        if ( !empty($_REQUEST[ 'debug' ]) ) {
            $random = rand(0, intval($_REQUEST[ 'debug' ]) );
            if ( $random === 0 ) {
                header("HTTP/1.0 500 Internal Server Error");
                exit;
            }
        }

        // header("HTTP/1.0 500 Internal Server Error");
        // exit;
        // 5 minutes execution time
        @set_time_limit(5 * 60);
        // Uncomment this one to fake upload time
        // usleep(5000);
        // Settings
        // $targetDir = ini_get("upload_tmp_dir") . DIRECTORY_SEPARATOR . "plupload";
        $targetDir = 'upload_tmp';
        $swtype=input('post.uptype');


        if ($swtype == 'file') {
            $configupload=config('app.swupload.file');
            $uploadDir = $configupload['updir'];
            $extArray = $configupload['file_type'];
            $upmaxsize = $configupload['max_size'];
//            $upmaxsize = 1024000;

        }else if($swtype == 'imgvideo')
        {
            $configupload=config('app.swupload.imgvideo');
            $uploadDir = $configupload['updir'];
            $extArray = $configupload['file_type'];
            $upmaxsize = $configupload['max_size'];
//            $upmaxsize = 1024000;
        }
        else {
            $swtype == 'image';
            $configupload=config('app.swupload.image');
            $uploadDir = $configupload['updir'];
            $extArray = $configupload['file_type'];
            $upmaxsize = $configupload['max_size'];
//            $upmaxsize = 1024000;
        }




        //$uploadDir = 'uploads'.DIRECTORY_SEPARATOR.'file_material';

        $uploadDir .= date('Y', time()) . '/' . date('m', time()) . date('d', time());

        // $targetDir = 'uploads'.DIRECTORY_SEPARATOR.'file_material_tmp';
        //$uploadDir = 'uploads'.DIRECTORY_SEPARATOR.'file_material';

        //echo $uploadDir;

        $cleanupTargetDir = true; // Remove old files
        $maxFileAge = 5 * 3600; // Temp file age in seconds
        // Create target dir
        /*     if (!file_exists($targetDir)) {
                 @mkdir($targetDir);
             }*/

        //上传文件夹权限设置 777拥有全部权限

        if (!file_exists($targetDir)) {
            @mkdir($targetDir,0777,true);
        }

        // Create target dir
        /*     if (!file_exists($uploadDir)) {
                 @mkdir($uploadDir);
             }*/

        if (!file_exists($uploadDir)) {
            @mkdir($uploadDir,0777,true);
        }

        // Get a file name
        /*  if (isset($_REQUEST["name"])) {
              $fileName = $_REQUEST["name"];
          } elseif (!empty($_FILES)) {
              $fileName = $_FILES["file"]["name"];
          } else {
              $fileName = uniqid("file_");
          }*/

        $fileName=input('post.name');
        $fileSize=input('post.size');
        $pathInfo = pathinfo($fileName);




        $fileName = $this->unicode2utf8('"' . $fileName . '"');
        //$fileName = iconv("GB2312", "UTF-8//IGNORE", $fileName);//防止fopen语句失效
        $fileName= iconv("UTF-8", "GBK", $fileName);//防止fopen语句失效


        if($fileSize>intval($upmaxsize))
        {
            die('{"jsonrpc" : "2.0", "error" : {"code": 10, "message": "错误：文件大小超出限制！"}, "id" : "id"}');
        }

        if(!in_array(strtolower($pathInfo['extension']),$extArray)){

            die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "错误：禁止上传该类型文件"}, "id" : "id"}');

        }

        $oldName = $fileName;
        $filePath = $targetDir . DIRECTORY_SEPARATOR . $fileName;
        // $uploadPath = $uploadDir . DIRECTORY_SEPARATOR . $fileName;
        // Chunking might be enabled
        $chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
        $chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 1;
        // Remove old temp files



        if ($cleanupTargetDir) {
            if (!is_dir($targetDir) || !$dir = opendir($targetDir)) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
            }
            while (($file = readdir($dir)) !== false) {
                $tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;
                // If temp file is current file proceed to the next
                if ($tmpfilePath == "{$filePath}_{$chunk}.part" || $tmpfilePath == "{$filePath}_{$chunk}.parttmp") {
                    continue;
                }
                // Remove temp file if it is older than the max age and is not the current file
                if (preg_match('/\.(part|parttmp)$/', $file) && (@filemtime($tmpfilePath) < time() - $maxFileAge)) {
                    @unlink($tmpfilePath);
                }
            }
            closedir($dir);
        }


        // Open temp file
        if (!$out = @fopen("{$filePath}_{$chunk}.parttmp", "wb")) {
            die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
        }
        if (!empty($_FILES)) {
            if ($_FILES["Filedata"]["error"] || !is_uploaded_file($_FILES["Filedata"]["tmp_name"])) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
            }
            // Read binary input stream and append it to temp file
            if (!$in = @fopen($_FILES["Filedata"]["tmp_name"], "rb")) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
            }
        } else {
            if (!$in = @fopen("php://input", "rb")) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
            }
        }
        while ($buff = fread($in, 4096)) {
            fwrite($out, $buff);
        }
        @fclose($out);
        @fclose($in);
        rename("{$filePath}_{$chunk}.parttmp", "{$filePath}_{$chunk}.part");
        $index = 0;
        $done = true;
        for( $index = 0; $index < $chunks; $index++ ) {
            if ( !file_exists("{$filePath}_{$index}.part") ) {
                $done = false;
                break;
            }
        }



        if ( $done ) {

            $hashStr = substr(md5($pathInfo['basename']),8,16);
            $hashName = time() . $hashStr . '.' .$pathInfo['extension'];
            $uploadPath = $uploadDir . '/' .$hashName;

            if (!$out = @fopen($uploadPath, "wb")) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
            }
            if ( flock($out, LOCK_EX) ) {
                for( $index = 0; $index < $chunks; $index++ ) {
                    if (!$in = @fopen("{$filePath}_{$index}.part", "rb")) {
                        break;
                    }
                    while ($buff = fread($in, 4096)) {
                        fwrite($out, $buff);
                    }
                    @fclose($in);
                    @unlink("{$filePath}_{$index}.part");
                }
                flock($out, LOCK_UN);
            }
            @fclose($out);

            $response = [
                'status'=>1,
                'msg'=>'上传文件成功',
                //'name'=>$oldName,
                'path'=>'/'.$uploadPath,
                'thumb'=>'/'.$uploadPath,
                'size'=>$fileSize,
                'ext'=>$pathInfo['extension']
                // 'file_id'=>$data['id'],
            ];

            die(json_encode($response));
        }

        // Return Success JSON-RPC response
        die('{"jsonrpc" : "2.0", "result" : null, "id" : "id"}');

    }


    public function unicode2utf8($str){
        if(!$str) return $str;
        $decode = json_decode($str);
        if($decode) return $decode;
        $str = '["' . $str . '"]';
        $decode = json_decode($str);
        if(count($decode) == 1){
            return $decode[0];
        }
        return $str;
    }

}