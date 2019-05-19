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
     * Get prizes from guild stash
     * 
     * @url GET /getPrizes/$tab
     * 
     * @param int $tab This is the index of the guild bank tabs
     *            0 = Guild Stash
     *            1 = Treasure Trove
     *            2 = Deep Cave
     * 
     */
    public function getPrizes( $tab = 0 ) {
        // Settings for the prizes
        $guild = $this->config['guilds'][0];

        // Query the API for the guild stash info based on $tab
        $stash = $this->api->guild()->stashOf( $guild['api_key'], $guild['guild_id'] )->get()[$tab];

        // Grab all the item ids
        $lookupIDs = array();
        foreach( $stash->inventory as $item ){
            if( is_object( $item ) ){
                $lookupIDs[$item->id] = $item->id;
            }
        }

        // fetch item info
        $itemInfo = $this->api->items()->many( $lookupIDs );
        
        $prizeArray = array();
        foreach( $stash->inventory as $item ) {
            if( is_null( $item ) )
                continue;
                
            // Find the item in the "Cache" (DIRTY)
            $apiItem = $this->_searchItems( $itemInfo, 'id', $item->id );
            $prizeArray[] = [
                "id"            => $item->id,
                "count"         => $item->count,
                "name"          => $apiItem->name,
                //description is optional
                "description"   => ( property_exists( $apiItem, 'description' ) ? $apiItem->description : "" ),
                "type"          => $apiItem->type,
                "rarity"        => strtolower( $apiItem->rarity ),
                "chat_link"     => $apiItem->chat_link,
                "icon"          => $apiItem->icon
            ];
        }

        return $prizeArray;
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

    /**
     * Search the array of item objects for $value in $index
     * 
     */
    private function _searchItems($array, $index, $value)
    {
        foreach($array as $arrayInf) {
            if($arrayInf->{$index} == $value) {
                return $arrayInf;
            }
        }
        return null;
    }
}

$server->addClass('GW2LottoryController');
$server->handle();