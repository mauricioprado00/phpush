<?php

class Phpush_Helper_Updown {

    public static $_uploaded_files = array();
    
    public static function DownloadFile($file) { // $file = include path 
        if (file_exists($file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . basename($file));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            @ob_clean();
            flush();
            readfile($file);
            exit;
        }
    }
    
    public static function getUploadedFiles()
    {
        return self::$_uploaded_files;
    }
    
    public static function clearUploadedFiles()
    {
        foreach (self::$_uploaded_files as $file) {
            unlink($file);
        }
    }

    public static function handleUploads() {
        
        for ($i = 0; isset($_POST['upload_name_' . $i], $_POST['upload_content_' . $i]); $i++) {
            $file = $_POST['upload_name_' . $i];
            $content = gzuncompress(base64_decode($_POST['upload_content_' . $i]));
            if (file_put_contents($file, $content) && file_exists($file)) {
                self::$_uploaded_files[] = $file;
            } else {
                throw new Exception("Could not upload file $file");
            }
        }
    }
    
    public static function remoteHandle()
    {
        self::handleUploads();
    }
    
    public static function remoteHandleFinish()
    {
        self::clearUploadedFiles();
    }

}
