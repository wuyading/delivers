<?php

namespace App\Plugins;

header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Headers: X-Requested-With,X_Requested_With');
class UploaderVideo
{

    function getUploadFiles()
    {

        $aFiles = $_FILES;
        $aMultiFiles = array();

        // 判断是否是分片上传
        $iChunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
        $iChunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;

        foreach ($aFiles as $sKey => $mFiles) {
            if (is_array($mFiles['name'])) {
                $iCnt = count($mFiles['name']);

                for ($i = 0; $i < $iCnt; ++$i) {
                    $aMultiFiles[] = array(
                        'key' => $sKey . '_' . $i,
                        'name' => $mFiles['name'][$i],
                        'tmp_name' => $mFiles['tmp_name'][$i],
                        'error' => $mFiles['error'][$i],
                        'size' => $mFiles['size'][$i],
                        'chunk' => $iChunk,
                        'chunks' => $iChunks,
                    );
                }
            } else {
                $aMultiFiles[] = array(
                    'key' => $sKey,
                    'name' => $mFiles['name'],
                    'tmp_name' => $mFiles['tmp_name'],
                    'error' => $mFiles['error'],
                    'size' => $mFiles['size'],
                    'chunk' => $iChunk,
                    'chunks' => $iChunks,
                );
            }
        }

        return $aMultiFiles;
    }

    /**
     * 将临时文件合并成正式文件
     */
    function saveMultiFiles($aFile)
    {
        $tmp_file_path = './tmp';
        $p_sName = $aFile['name'];
        $p_sNameFilename = pathinfo($p_sName, PATHINFO_FILENAME);
        $p_sFilePath = $tmp_file_path . DIRECTORY_SEPARATOR . $p_sNameFilename;

        $p_sFilenamePath = $tmp_file_path . DIRECTORY_SEPARATOR . $p_sName;

        if (!file_exists($p_sFilenamePath)) {
            fopen($p_sFilenamePath, "w");
        }

        $p_sTmpName = $aFile['tmp_name'];
        $p_iError = $aFile['error'];
        $p_iSize = $aFile['size'];
        $iChunk = $aFile['chunk'];
        $iChunks = $aFile['chunks'];
        $iError = 0;


        if ($p_iError > 0) {
            // 文件上传出错
            $msg=['error'=>1,'msg'=>'文件上传出错'];
            return $msg;
        }

        if (!is_uploaded_file($p_sTmpName)) {
            $msg=['error'=>2,'msg'=>'upload error, use http post to upload'];
            return $msg;
        }

        $oFInfo = finfo_open();
        $sMimeType = finfo_file($oFInfo, $p_sTmpName, FILEINFO_MIME_TYPE);

        finfo_close($oFInfo);

        $sExtension = pathinfo($p_sName, PATHINFO_EXTENSION);

        if (empty($sExtension)) {
            $msg=['error'=>2,'msg'=>'upload error, use http post to upload'];
            return $msg;
        }

        if (!$in = @fopen($p_sTmpName, "rb")) {
            $msg=['error'=>3,'msg'=>'Failed to open input stream'];
            return $msg;
        }

        if (!$out = @fopen("{$p_sFilePath}_{$iChunk}.parttmp", "wb")) {

            $msg=['error'=>3,'msg'=>'Failed to open input stream'];
            return $msg;
        }

        while ($buff = fread($in, 4096)) {
            fwrite($out, $buff);
        }
        @fclose($out);
        @fclose($in);

        rename("{$p_sFilePath}_{$iChunk}.parttmp", "{$p_sFilePath}_{$iChunk}.part");

        $done = true;
        for ($index = 0; $index < $iChunks; $index++) {
            if (!file_exists("{$p_sFilePath}_{$index}.part")) {
                $done = false;
                break;
            }
        }


        if ($done) {

            $sDestFile = './upload/' . time() . '.' . $sExtension;      //合并文件地址

            if (!$out = @fopen($sDestFile, "wb")) {
                $msg=['error'=>3,'msg'=>'Failed to open input stream'];
                return $msg;
            }

            $sFileSize = 0;

            if (flock($out, LOCK_EX)) {
                for ($index = 0; $index < $iChunks; $index++) {
                    if (!$in = @fopen("{$p_sFilePath}_{$index}.part", "rb")) {
                        break;
                    }

                    while ($buff = fread($in, 4096)) {
                        fwrite($out, $buff);
                    }
                    @fclose($in);
                    @unlink("{$p_sFilePath}_{$index}.part");
                }
                flock($out, LOCK_UN);
            }
            @fclose($out);

            // 删除临时文件
            @unlink($p_sFilenamePath);

        }

        return true;

    }
}