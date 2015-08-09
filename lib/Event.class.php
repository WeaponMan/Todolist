<?php

class Event {
    const PASSWORD_CHANGE = 1;
    const EMAIL_CHANGE_OLD = 2;
    const EMAIL_CHANGE_NEW = 3;
    
    public static function newKey(){
        $onePart = \Snabb\Cryptography::random(10);
        $twoPart = \Snabb\Cryptography::random(10);
        $thirdPart = \Snabb\Cryptography::random(11);
        return sha1(\Snabb\Cryptography::random(50, $onePart . $twoPart . $thirdPart));
    }

    public static function add(\Snabb\Database\Connection $db, $key, $user_id, $type, $expire, $value = null){
        return $db->insert('events', ['event_key' => $key, 'user_id' => $user_id, 'event_type' => (int) $type, 'event_expire' => (int)$expire, 'event_value' =>($value === null ? new \Snabb\Database\Literal('null'): $value)]) !== false;
    }
    
    public static function setComplete(\Snabb\Database\Connection $db ,$key){
        return $db->exec('UPDATE events SET event_complete = 1 WHERE event_key = '.$db->quote($key)) !== false;
    }
    
    public static function isActive(\Snabb\Database\Connection $db ,$key){
        $data = $db->query('SELECT event_key FROM events WHERE event_key = '.$db->quote($key).' AND event_complete = 0 AND event_expire > '.$db->quote(time()))->fetch();
        return ($data !== false) ? true : false; 
    }
    
    public static function getData(\Snabb\Database\Connection $db ,$key){
        $data = $db->query('SELECT event_value FROM events WHERE event_key = '.$db->quote($key))->fetch();
        return ($data !== false and $data['event_value'] !== null) ? $data['event_value'] : false;
    }
}
