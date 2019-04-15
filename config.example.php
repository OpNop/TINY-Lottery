<?php 

if( !defined( 'ISTINY' ) ) { 
    header('HTTP/1.1 404 Not Found'); 
    die();
 }

$config = [
    'guilds'    =>  [
        [
            'guild_id'  => '', # Guild ID from the API XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX
            'api_key'   => '' # API key from https://account.arena.net/applications (just needs Account & Guild)
        ],
    ],   
    'db_server' => 'localhost',
    'db_user'   => 'root',
    'db_pass'   => 'password',
    'db_name'   => '',
    'db_table'  => ''
];