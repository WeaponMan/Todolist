<?php

class User {

    private $db, $user_id, $nick, $email, $admin;
    private static $salt = 'LkjdasDSA.Ů-SDssa5d4a8wešěřč412';

    public function __construct(\Snabb\Database\Connection $db, array $data) {
        $this->db = $db;
        $this->user_id = (int) $data['user_id'];
        $this->nick = $data['nick'];
        $this->email = $data['email'];
        if($data['app_admin_from'] !== null)
            $this->admin = (int) $data['app_admin_from'];
        else
            $this->admin = null;
    }

    public function listOwnerOrAdmin($list_id) {
        if ($this->db->query('SELECT list_id FROM lists WHERE user_id = ' . $this->user_id . ' AND list_id = ' . (int) $list_id)->fetch() !== false)
            return true;
        if ($this->db->query('SELECT list_id FROM list_users WHERE user_id =' . $this->user_id . ' AND list_id = ' . (int) $list_id . ' AND list_admin_from IS NOT NULL')->fetch() !== false)
            return true;
        return false;
    }
    
    public function isAppAdmin(){
        return $this->admin !== null;
    }

    public function listMember($list_id) {
        return $this->db->query('SELECT list_id FROM list_users WHERE user_id =' . $this->user_id . ' AND list_id = ' . (int) $list_id . ' AND member_from IS NOT NULL')->fetch() !== false;
    }

    public static function list_member(\Snabb\Database\Connection $db, $user_id, $list_id) {
        return $db->query('SELECT list_id FROM list_users WHERE user_id =' . (int) $user_id . ' AND list_id = ' . (int) $list_id . ' AND member_from IS NOT NULL')->fetch() !== false;
    }

    public static function list_owner_or_admin(\Snabb\Database\Connection $db, $user_id, $list_id) {
        if ($db->query('SELECT list_id FROM lists WHERE user_id = ' . (int) $user_id . ' AND list_id = ' . (int) $list_id)->fetch() !== false)
            return true;
        if ($db->query('SELECT list_id FROM list_users WHERE user_id =' . (int) $user_id . ' AND list_id = ' . (int) $list_id . ' AND list_admin_from IS NOT NULL')->fetch() !== false)
            return true;
        return false;
    }

    public static function hashPassword($nick, $password) {
        return sha1(self::$salt . sha1($nick . self::$salt) . sha1($password . self::$salt));
    }

    public function __get($name) {
        return isset($this->$name) ? $this->$name : null;
    }

}
