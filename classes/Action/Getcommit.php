<?php

#./phpush.php -p"si podes ejecutar esto tranquilo" -h"http://localhost/testssync/gs.php" -agetcommit --last-commit-file=last_commit
class Phpush_Action_Getcommit extends Phpush_Action_Abstract
{
    private function _getArgs()
    {
        $short = '';
        $long = array(
            'last-commit-file:'
        );

        return getopt($short, $long);
    }
    
    /**
     * checks the required parameters and returns the errors
     * @return array
     */
    public function getParametersErrors($errors = array()) 
    {
        $args = $this->_getArgs();
        if (!isset($args['last-commit-file'])) {
            $errors[] = "you must specify the last commit filename";
        }
        
        return $errors;
    }
    
    /**
     * returns the usage of the action
     * @return string
     */
    public function getActionUsage()
    {
        return '[last-commit-file=last_commit]';
    }

    /**
     * prepares the remote execution
     * @param Phpush_Helper_RemoteExecutor $remoteExecutor
     */
    public function beforeExecute($remoteExecutor) 
    {
        $args = $this->_getArgs();
        $remoteExecutor->addPostVariable('last_commit_file', $args['last-commit-file']);
    }
    
    /**
     * executes the comand and returns the result
     * @return boolean | mixed
     */
    public function _remoteExecution()
    {
        if (file_exists($_POST['last_commit_file'])) {
            return file_get_contents($_POST['last_commit_file']);
        }
        
        return '';
    }
}