<?php
/*
    classes/Database.php
    ---------------------------------------------
    Topic 17: Object Oriented Programming (OOP)

    This class wraps mysqli in a few simple, reusable methods
    so the rest of our code stays short and readable.
    We use PREPARED STATEMENTS here to stop SQL Injection attacks,
    which is a beginner-friendly but important security habit.
*/

class Database {
    private $conn;

    // The constructor runs automatically when we create "new Database()"
    public function __construct($connection) {
        $this->conn = $connection;
    }

    // Run a SELECT query safely and return all rows as an array
    // $types example: "si" means (string, integer)
    public function select($sql, $types = "", $params = []) {
        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) {
            return [];
        }
        if ($types !== "") {
            mysqli_stmt_bind_param($stmt, $types, ...$params);
        }
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $rows = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        mysqli_stmt_close($stmt);
        return $rows;
    }

    // Run a SELECT query and return just ONE row (or null)
    public function selectOne($sql, $types = "", $params = []) {
        $rows = $this->select($sql, $types, $params);
        return count($rows) > 0 ? $rows[0] : null;
    }

    // Run an INSERT / UPDATE / DELETE query
    public function run($sql, $types = "", $params = []) {
        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) {
            return false;
        }
        if ($types !== "") {
            mysqli_stmt_bind_param($stmt, $types, ...$params);
        }
        $success = mysqli_stmt_execute($stmt);
        $insertId = mysqli_insert_id($this->conn);
        mysqli_stmt_close($stmt);

        return $success ? $insertId : false;
    }
}
?>
