<?php

/* Json entry for deposit
{
    "id": 52573,
    "time": "2019-01-05T01:38:16.000Z",
    "type": "stash",
    "user": "AccountName.1234",
    "operation": "deposit",
    "item_id": 0,
    "count": 0,
    "coins": 10000
}
*/

define( 'ISTINY', true );
//if isNotTiny, shun user.

require_once( '../config.php' );
require_once( '../vendor/autoload.php' );
date_default_timezone_set('UTC');

// Create new api instance
$api = new \GW2Treasures\GW2Api\GW2Api();

// Create connection
$conn = new mysqli( $config['db_server'], $config['db_user'], $config['db_pass'], $config['db_name'] );
if ($conn->connect_error) {
    error_log( "Connection failed: {$conn->connect_error}" );
    die();
}

// Get last ID in the DB
$last_id = $conn->query( "SELECT MAX(id) as id FROM {$config['db_table']}" )->fetch_object()->id;

if( is_null( $last_id ) )
    $last_id = 0;

// Call the GW2 API and fetch the log
$log = $api->guild()->logOf( $config['api_key'], $config['guild_id'] )->since( $last_id );

// If the API call failed, or empty log. just die
if( empty( $log ) ) {
    error_log( "GW2 API call failed or empty" );
    die();
}

foreach ( $log as $entry ) {
    //var_dump( $entry );
    if( $entry->id > $last_id ) {
        if( $entry->type == 'stash' && $entry->operation == 'deposit' && $entry->coins > 0 ) {
            $result = $conn->query( "INSERT INTO `{$config['db_table']}` (`id`, `time`, `user`, `coins`) 
                                     VALUES ({$entry->id}, STR_TO_DATE('{$entry->time}', '%Y-%m-%dT%H:%i:%s.000Z'), '{$entry->user}', {$entry->coins})" );
        }
    }
}