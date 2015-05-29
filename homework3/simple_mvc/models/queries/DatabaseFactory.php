<?php
class DatabaseFactory
{
    /**
     * 
     * @return \PDO
     */
    public static function getDefaultPdoObject()
    {
        $connection_string = sprintf('%s:host=%s;dbname=%s', 
                                     'mysql',
                                     'localhost',
                                     'sakila'
                                     );
        $db = new PDO($connection_string, 'cpts483', 'cpts483');
        return $db;
    }
    
    public static function getPdoObject($db_config)
    {
         $connection_string = sprintf('%s:host=%s;dbname=%s', 
                                     $db_config->dbType,
                                     $db_config->dbHost,
                                     $db_config->dbName
                                     );
        $db = new PDO($connection_string, $db_config->dbUser, $db_config->dbPass);
        return $db;
    }
}
?>
