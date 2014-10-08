<?php

abstract class Phpush_Action_Abstract
{
    static private $_current_action = null;
    private $_action_name = null;
    
    protected $_args = array();
    
    /**
     * instanciates an action
     * @param string $action
     * @return Phpush_Action_Abstract
     */
    public static function getAction($action)
    {
        $action = ucfirst(strtolower($action));
        $class = 'Phpush_Action_' . $action;
        
        if (class_exists($class)) {
            self::$_current_action = new $class;
            self::$_current_action->_action_name = $action;
            return self::$_current_action;
        }
    }
    
    /**
     * returns the current action being executed
     * @return Phpush_Action_Abstract
     */
    public static function getCurrentAction()
    {
        return self::$_current_action;
    }
    
    public static function getUsage()
    {
        $action = self::getCurrentAction();
        if (isset($action)) {
            return '-a' . self::$_current_action->_action_name . ' ' . $action->getActionUsage();
        } 
        
        return '-a[action]  (actions: ' . implode(', ', self::getValidActions()) . ')';
    }
    
    /**
     * returns the valid actions
     * @return array
     */
    public static function getValidActions()
    {
        return array(
            'push',
            'getcommit',
        );
    }
    
    /**
     * determines if an action is valid or not
     * @param string $action
     * @return boolean
     */
    public static function isValidAction($action)
    {
        return in_array($action, self::getValidActions());
    }
    
    /**
     * setst the arguments
     * @param array $args
     * @return \Phpush_Action_Abstract
     */
    public function setArgs($args)
    {
        $this->_args = $args;
        return $this;
    }
    
    /**
     * returns an initialized remote executor
     * @return Phpush_Helper_RemoteExecutor
     */
    public function getRemoteExecutor()
    {
        $exec = new Phpush_Helper_RemoteExecutor();
        return $exec
            ->setUrl($this->_args['h'])
            ->setPassword('si podes ejecutar esto tranquilo');
    }
    
    /**
     * checks the required parameters and returns the errors
     * @return array
     */
    abstract public function getParametersErrors($errors = array());
    
    /**
     * returns the usage of the action
     * @return string
     */
    abstract public function getActionUsage();
    
    /**
     * runs the action
     */
    public function run()
    {
        $remoteExecutor = $this->getRemoteExecutor();
        $this->beforeExecute($remoteExecutor);
        $code = <<<CODESET
                
        Phpush_Action_Abstract::getAction("{$this->_action_name}")->remoteExecution();
CODESET;
        
        $remoteExecutor->exec($code);
    }
    
    /**
     * prepares the remote execution
     * @param Phpush_Helper_RemoteExecutor $remoteExecutor
     */
    abstract public function beforeExecute($remoteExecutor);
                
    /**
     * executes the action
     * @return boolean | mixed
     */
    abstract public function _remoteExecution();
    
    public function remoteExecution()
    {
        $res = $this->_remoteExecution();
        if (is_bool($res)) {
            if ($res) {
                echo "phpush_ok";
            } else {
                echo "phpush_ko";
            }
        } else {
            echo $res;
        }
    }
}