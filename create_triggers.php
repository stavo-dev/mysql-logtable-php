<?php

require_once('configure.php');

echo 'DELIMITER ;;'."\n";

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

    $query = "SELECT COLUMN_NAME AS column_name FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=? AND TABLE_NAME=? ORDER BY ORDINAL_POSITION ASC;";
    if($stmt = $mysqli->prepare($query)):
        $stmt->bind_param(
            'ss',
            $database, 
            $table['table_name']
        );
        $stmt->execute();
        $columns = $stmt->get_result();
        $row_count = $columns->num_rows;
    endif;

    foreach($event_types as $event_type) {

        $i = $row_count;

echo '
-- ----------------------------
-- Trigger for trigger_'.$table['table_name'].'_'.$event_type.'
-- ----------------------------
DROP TRIGGER IF EXISTS trigger_'.$table['table_name'].'_'.$event_type.';;
CREATE TRIGGER trigger_'.$table['table_name'].'_'.$event_type.' AFTER '.strtoupper($event_type).' ON '.$table['table_name'].'
FOR EACH ROW
BEGIN

    INSERT INTO __'.$table['table_name'].'
    SET action = \''.$event_type.'\','."\n\t\t";

    foreach($columns as $column) {        
        echo $column['column_name'].' = NEW.'.$column['column_name'];
        if($i > 1): echo ','; else: echo ';'; endif;
        echo "\n\t\t";
        $i--;
    }

echo '
END;;'."\n\n";

    }

}

echo 'DELIMITER ;';
