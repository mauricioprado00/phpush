<?php

class Phpush_Helper_RemoteExecutor
{
    /**
     *
     * @var string
     */
    private $_password;
    
    /**
     * files to be uploaded
     * @var array
     */
    private $_uploads = array();
    
    /**
     * remote url where it's executed
     * @var string
     */
    private $_url;
    
    /**
     * queue file to upload
     */
    public function addUploadFile($file)
    {
        if (!file_exists($file)) {
            die("file $file does not exists");
        }
        
        $this->_uploads[basename($file)] = base64_encode(gzcompress(file_get_contents($file)));
        return $this;
    }
    
    /**
     * sets the password
     * @param string $password
     * @return \Phpush_Helper_RemoteExecutor
     */
    public function setPassword($password)
    {
        $this->_password = $password;
        return $this;
    }
    
    /**
     * sets the target url
     * @param string $url
     * @return \Phpush_Helper_RemoteExecutor
     */
    public function setUrl($url)
    {
        $this->_url = $url;
        return $this;
    }
    
    public function exec($exec)
    {
        $code = Phpush_Helper_PackageLib::packageLib();
        $code .= trim($exec . ';' . Phpush_Helper_PackageLib::handleFinish());
        $firma = sha1($code . $this->_password);
        
        $post['firma'] = $firma;
        $post['script'] = $code;
        $target_url = $this->_url;
        
        $i = 0;
        foreach($this->_uploads as $name => $upload) {
            $post['upload_name_' . $i] = $name;
            $post['upload_content_' . $i++] = $upload;
        }

        //$file_name_with_full_path = realpath('./sample.jpeg');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $target_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        //curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        
        return $result;
    }
}