<?php
namespace TV\Core;
use PDO;
class Settings {
    private PDO $db; private array $cache=[];
    public function __construct(PDO $db){$this->db=$db;}
    public function get(string $key,$default=null){
        if(array_key_exists($key,$this->cache)) return $this->cache[$key];
        $s=$this->db->prepare('SELECT value FROM settings WHERE `key`=?');$s->execute([$key]);$v=$s->fetchColumn();
        return $this->cache[$key]=$v===false?$default:$v;
    }
    public function set(string $key,$value): void { $s=$this->db->prepare('INSERT INTO settings (`key`,`value`) VALUES (?,?) ON DUPLICATE KEY UPDATE value=VALUES(value)');$s->execute([$key,(string)$value]);$this->cache[$key]=(string)$value; }
    public function many(array $keys): array { $o=[];foreach($keys as $k=>$d)$o[$k]=$this->get($k,$d);return $o; }
}
