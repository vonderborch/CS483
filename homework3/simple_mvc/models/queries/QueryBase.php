<?php
abstract class QueryBase
{
    private $_db;
    
    /**
    * @desc 
    * @param DatabaseConfig $db_config
    */
    public function __construct($db_config = null)
    {
        if(empty($db_config))
        {
            $this->_db = DatabaseFactory::getDefaultPdoObject();
        }
        else
        {
            $this->_db = DatabaseFactory::getPdoObject($db_config);
        }
    }
    
    public function __get($name)
    {
        $name = '_' . $name;
        return $this->$name;
    }
    
    public abstract function execute();
}  
?>
