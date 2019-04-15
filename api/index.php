<?php
define( 'ISTINY', true );
require_once '../config.php';
require_once '../vendor/autoload.php';
date_default_timezone_set('UTC');

use \Jacwright\RestServer\RestServer;
use \Jacwright\RestServer\RestException;
use \GW2Treasures\GW2Api\GW2Api;

//Fire up the Rest Server
$server = new RestServer('debug');

class GW2LottoryController {

    private $config;
    private $api;
    private $conn;
    
    public function __construct( ) {
        global $config;
        $this->config = $config;

        $this->api = new GW2Api();
        $this->conn = new mysqli($this->config['db_server'], $this->config['db_user'], $this->config['db_pass'], $this->config['db_name']);
        if ($this->conn->connect_error) {
            error_log( "Connection failed: {$this->conn->connect_error}" );
            throw new RestException( 500, 'Database connection failure' );
        }
    }

	/**
     * Search for lottery entries
     * 
     * @url GET /listEntries/$account
     */
    public function listEntries( $account ) {
        $result = $this->conn->query( "SELECT * FROM `{$this->config['db_table']}` WHERE `user`='{$account}' ORDER BY `time` DESC" )->fetch_all( MYSQLI_ASSOC );
        return $result;
    }

    /**
     * Test DB (REMOVE FOR PRODUCTION)
     * 
     * @url GET /test
     */
    public function test() {
        $result = $this->conn->query( "SELECT * FROM `{$this->config['db_table']}` ORDER BY `time` DESC" )->fetch_all( MYSQLI_ASSOC );
        return $result;
    }
}

$server->addClass('GW2LottoryController');
$server->handle();