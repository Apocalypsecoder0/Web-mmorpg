
<?php
// MySQL to PDO compatibility layer
// This file provides compatibility functions for old MySQL code

if (!function_exists('mysql_fetch_object')) {
    function mysql_fetch_object($result) {
        if ($result instanceof PDOStatement) {
            $row = $result->fetch(PDO::FETCH_OBJ);
            return $row;
        }
        return false;
    }
}

if (!function_exists('mysql_fetch_array')) {
    function mysql_fetch_array($result, $result_type = MYSQL_BOTH) {
        if ($result instanceof PDOStatement) {
            $fetchMode = PDO::FETCH_BOTH;
            if ($result_type == MYSQL_ASSOC) {
                $fetchMode = PDO::FETCH_ASSOC;
            } elseif ($result_type == MYSQL_NUM) {
                $fetchMode = PDO::FETCH_NUM;
            }
            return $result->fetch($fetchMode);
        }
        return false;
    }
}

if (!function_exists('mysql_num_rows')) {
    function mysql_num_rows($result) {
        if ($result instanceof PDOStatement) {
            return $result->rowCount();
        }
        return false;
    }
}

// Define MySQL constants if not defined
if (!defined('MYSQL_BOTH')) {
    define('MYSQL_BOTH', 1);
    define('MYSQL_ASSOC', 2);
    define('MYSQL_NUM', 3);
}
?>
