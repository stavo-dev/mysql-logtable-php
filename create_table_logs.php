<?php

require_once('configure.php');

$query = "SELECT TABLE_NAME AS table_name FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA=? AND TABLE_TYPE='BASE TABLE' AND LEFT (TABLE_NAME,2)<> '__' ORDER BY TABLE_NAME ASC;";
if($stmt = $mysqli->prepare($query)):
    $stmt->bind_param(
        's',
        $database
    );
    $stmt->execute();
    $tables = $stmt->get_result();
endif;

foreach($tables as $table) {

    $query = "SELECT COLUMN_NAME AS column_name,DATA_TYPE AS data_type FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=? AND TABLE_NAME=? AND COLUMN_KEY='PRI';";
    if($stmt = $mysqli->prepare($query)):
        $stmt->bind_param(
            'ss',
            $database,
            $table['table_name']
        );
        $stmt->execute();
        $columns = $stmt->get_result();
    endif;

    $query = "SELECT COLUMN_NAME AS column_name,DATA_TYPE AS data_type,CHARACTER_MAXIMUM_LENGTH AS character_maximum_length FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=? AND TABLE_NAME=? ORDER BY ORDINAL_POSITION ASC;";
    if($stmt = $mysqli->prepare($query)):
        $stmt->bind_param(
            'ss',
            $database,
            $table['table_name']
        );
        $stmt->execute();
        $columns = $stmt->get_result();
    endif;

echo '-- ----------------------------
-- Table structure for __'.$table['table_name'].'
-- ----------------------------
DROP TABLE IF EXISTS __'.$table['table_name'].';
CREATE TABLE __'.$table['table_name'].' (
    id INT NOT NULL AUTO_INCREMENT,
    action CHAR ( 6 ),'."\n\t";

    foreach($columns as $column) {        
        echo $column['column_name'].' '.strtoupper($column['data_type']);
        if(!empty($column['character_maximum_length'])):
            echo ' ( '.$column['character_maximum_length'].' )';
        endif;
        echo ','."\n\t";
    }

echo 'PRIMARY KEY ( id )
);'."\n\n";

}
