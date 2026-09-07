<?php
namespace TV\Services;
use PDO;
class Audit {
    public static function log(PDO $db,string $action,?string $entity=null,?int $id=null,?string $description=null,array $meta=[]): void {
        try{$s=$db->prepare('INSERT INTO audit_logs(user_id,action,entity_type,entity_id,description,ip_address,meta) VALUES(?,?,?,?,?,?,?)');$s->execute([$_SESSION['user']['id']??null,$action,$entity,$id,$description,$_SERVER['REMOTE_ADDR']??null,$meta?json_encode($meta,JSON_UNESCAPED_UNICODE):null]);}catch(\Throwable $e){}
    }
}
