<?php

class Phpush_Action_Push extends Phpush_Action_Abstract
{
    /**
     * checks the required parameters and returns the errors
     * @return array
     */
    public function getParametersErrors($errors = array()) 
    {
        $short = '';
        $long = array(
            'push-file:'
        );

        $args = getopt($short, $long);
        
        if (!isset($args['push-file'])) {
            $errors[] = 'missing --push-file parameter';
        }
        
        if (!file_exists($args['push-file'])) {
            $errors[] = "specified file '{$args['push-file']}' doesn't exists";
        }
        
        return $errors;
    }
    
    /**
     * returns the usage of the action
     * @return string
     */
    public function getActionUsage()
    {
        return '[--push-file=/path/to/file.tgz]';
    }

    /**
     * prepares the remote execution
     * @param Phpush_Helper_RemoteExecutor $remoteExecutor
     */
    public function beforeExecute($remoteExecutor) 
    {
        $short = '';
        $long = array(
            'push-file:'
        );

        $args = getopt($short, $long);
        
        $remoteExecutor
            ->addUploadFile($args['push-file']);
    }
    
    /**
     * executes the comand and returns the result
     * @return boolean | mixed
     */
    public function _remoteExecution()
    {
        $f = null;
        foreach(Phpush_Helper_Updown::getUploadedFiles() as $file) {
            $f = `tar -zxvf $file`;
        }
        
        return $f ? true: false;
    }
}