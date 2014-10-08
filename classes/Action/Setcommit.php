<?php

#./phpush.php -p"si podes ejecutar esto tranquilo" -h"http://localhost/testssync/gs.php" -asetcommit --last-commit-file=last_commit
class Phpush_Action_Setcommit extends Phpush_Action_Abstract
{
    private function _getArgs()
    {
        $short = '';
        $long = array(
            'last-commit-file:',
            'commit:',
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
        if (!isset($args['commit'])) {
            $errors[] = "you must specify the commit id to set";
        }
        
        return $errors;
    }
    
    /**
     * returns the usage of the action
     * @return string
     */
    public function getActionUsage()
    {
        return '[last-commit-file=last_commit] [commit=A98D3E3F32F]';
    }

    /**
     * prepares the remote execution
     * @param Phpush_Helper_RemoteExecutor $remoteExecutor
     */
    public function beforeExecute($remoteExecutor) 
    {
        $args = $this->_getArgs();
        $remoteExecutor
            ->addPostVariable('last_commit_file', $args['last-commit-file'])
            ->addPostVariable('commit', $args['commit'])
        ;
    }
    
    /**
     * executes the comand and returns the result
     * @return boolean | mixed
     */
    public function _remoteExecution()
    {
        return file_put_contents($_POST['last_commit_file'], $_POST['commit']) > 0;
    }
}