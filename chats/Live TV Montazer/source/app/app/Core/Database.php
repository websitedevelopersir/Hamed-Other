<?php
namespace TV\Core;
use PDO; use PDOException;
class Database {
    private static ?PDO $pdo = null;
    public static function connect(array $cfg): PDO {
        if (self::$pdo) return self::$pdo;
        $d=$cfg['db'];
        $dsn="mysql:host={$d['host']};port={$d['port']};dbname={$d['name']};charset={$d['charset']}";
        self::$pdo=new PDO($dsn,$d['user'],$d['pass'],[
            PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES=>false,
        ]);
        return self::$pdo;
    }
}
