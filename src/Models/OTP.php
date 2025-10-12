<?php
namespace Models;
use PDO;

class OTP {
    private $conn;
    private $table = 'otp_codes';

    public function __construct(PDO $db){
        $this->conn = $db;
    }

    public function create($user_id, $code, $type, $expires_at){
        $sql = "INSERT INTO {$this->table} (user_id, code, type, expires_at) VALUES (:uid, :code, :type, :expires)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':uid'=>$user_id,
            ':code'=>$code,
            ':type'=>$type,
            ':expires'=>$expires_at
        ]);
    }

    public function validate($user_id, $code, $type){
        $sql = "SELECT * FROM {$this->table} WHERE user_id=:uid AND code=:code AND type=:type AND used=0 AND expires_at > NOW() LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':uid'=>$user_id,':code'=>$code,':type'=>$type]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function markUsed($id){
        $sql = "UPDATE {$this->table} SET used=1 WHERE id=:id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id'=>$id]);
    }
}
