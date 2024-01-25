<?php

class ClassTableCreation
{
    private $dbConnection;

    public function __construct($dbConnection)
    {
        $this->dbConnection = $dbConnection;
    }

    public function createDatabaseTables()
    {
        $sqlProductTable = "CREATE TABLE IF NOT EXISTS `todos` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `title` varchar(128) NOT NULL,
            `status` boolean NOT NULL DEFAULT 0,
            `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`)
            );";

        if ($this->dbConnection->query($sqlProductTable) === FALSE)
        {
            echo "Error creating table ";
        }
    }
}
