<?php

class DB
{
    private static function connect()
    {
        $dbhost = "localhost";
        $dbuser = "root";
        $dbpass = "";
        $dbname = "zipmoney";
        $pdo = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $pdo;
    }

    public static function query($query, $params = array())
    {
        $stmt = self::connect()->prepare($query);
        $stmt->execute($params);
        if (explode(' ', $query)[0] == 'SELECT') {
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $data;
        }
    }
}
