<?php

/** ---------------------------------------
 * Construct parameters
 * ------------------------------------- */
$hostname = '{hostname}';
$username = '{username}';
$password = '{password}';
$database = '{database}';

$mysqli = new mysqli($hostname, $username, $password, $database);

if(mysqli_connect_errno()):
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
else:
    /* echo '<p>Success: '.$data->host_info."</p>\n"; */
endif;

/** ---------------------------------------
 * Arrays
 * ------------------------------------- */
$event_types = ['insert', 'update'];
$columns = [];
$tables = [];

/** ---------------------------------------
 * Variables
 * ------------------------------------- */
$i = 0;
