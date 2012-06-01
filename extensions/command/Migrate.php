<?php
namespace li3_liquibase\extensions\command;

use lithium\data\Connections;
use lithium\util\String;

class Migrate extends \lithium\console\Command {
    public $connection = 'default';
    public $changelog = 'app/config/db.changelog.xml';
    public $liquibase = 'liquibase';

    private $command = 
        "{:liquibase} --driver={:driver} \
            --changeLogFile={:changelog} \
            --url=\"{:url}\" \
            --username={:username} \
            --password={:password} \
            migrate";


    public function run() {
        if(!$this->connection = Connections::get($this->connection)) {
            $this->error("Could not load connection '{$this->connection}'.");
            return false;
        }


        $command = $this->_getCommand(array(
            'liquibase' => $this->liquibase,
            'username' => $this->connection->_config['login'],
            'password' => $this->connection->_config['password'],
            'driver' => 'com.mssql.jdbc.Driver',
            'url' => "jdbc:mssql://{$this->connection->_config['host']}/{$this->connection->_config['database']}",
            'changelog' => $this->changelog
        ));

        exec($command);
    }

    private function _getCommand($config) {
        return String::insert($this->command, $config);
    }


}
