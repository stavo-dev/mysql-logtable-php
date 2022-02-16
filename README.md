<div id="top"></div>

### mysql-logtable-php

mysql-logtable-php is a set of PHP scripts which leverage MySQL INFORMATION_SCHEMA to create log tables and insert / update triggers.

### Configuration

Get started by entering your database credentials in configure.php.

    6   $hostname = '{hostname}';
    7   $username = '{username}';
    8   $password = '{password}';
    9   $database = '{database}';

### Execute

Execute the scripts from your favorite browser by visiting:

    1   /mysql-logtable-php/create_table_logs.php
    2   /mysql-logtable-php/create_triggers.php

The browser will display minified code. Go to view source for formatted code.

### Demo

#### Schema for demo_mysql_logtable_php

    CREATE DATABASE demo_mysql_logtable_php CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
    USE demo_mysql_logtable_php;

    DROP TABLE IF EXISTS client;
    CREATE TABLE client (
        client_id INT NOT NULL AUTO_INCREMENT,
        client_email VARCHAR ( 50 ) NOT NULL,
        client_passcode_hash BINARY ( 128 ),
        client_salt CHAR ( 36 ),
        last_modified_datetime TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY ( client_id ),
        CONSTRAINT unique_client_email UNIQUE ( client_email )
    );

#### Output

    1   http://localhost/mysql-logtable-php/create_table_logs.php

    -- ----------------------------
    -- Table structure for __client
    -- ----------------------------
    DROP TABLE IF EXISTS __client;
    CREATE TABLE __client (
        id INT NOT NULL AUTO_INCREMENT,
        action CHAR ( 6 ),
        client_id INT,
        client_email VARCHAR ( 50 ),
        client_passcode_hash BINARY ( 128 ),
        client_salt CHAR ( 36 ),
        last_modified_datetime TIMESTAMP,
        PRIMARY KEY ( id )
    );


    2   http://localhost/mysql-logtable-php/create_triggers.php

    DELIMITER ;;

    -- ----------------------------
    -- Trigger for trigger_client_insert
    -- ----------------------------
    DROP TRIGGER IF EXISTS trigger_client_insert;;
    CREATE TRIGGER trigger_client_insert AFTER INSERT ON client
    FOR EACH ROW
    BEGIN

        INSERT INTO __client
        SET action = 'insert',
            client_id = NEW.client_id,
            client_email = NEW.client_email,
            client_passcode_hash = NEW.client_passcode_hash,
            client_salt = NEW.client_salt,
            last_modified_datetime = NEW.last_modified_datetime;
            
    END;;

    -- ----------------------------
    -- Trigger for trigger_client_update
    -- ----------------------------
    DROP TRIGGER IF EXISTS trigger_client_update;;
    CREATE TRIGGER trigger_client_update AFTER UPDATE ON client
    FOR EACH ROW
    BEGIN

        INSERT INTO __client
        SET action = 'update',
            client_id = NEW.client_id,
            client_email = NEW.client_email,
            client_passcode_hash = NEW.client_passcode_hash,
            client_salt = NEW.client_salt,
            last_modified_datetime = NEW.last_modified_datetime;
            
    END;;

    DELIMITER ;

### Contact

If you are having issues, please create an issue on GitHub.

Project Link: [https://github.com/stavo-dev/mysql-logtable-php](https://github.com/stavo-dev/mysql-logtable-php)

### License

Distributed under the MIT License. See `LICENSE.txt` for more information.

<p align="right">(<a href="#top">back to top</a>)</p>
