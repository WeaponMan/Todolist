<?php

class Config implements ArrayAccess {

    const JSON_FILE = 1;
    const INI_FILE = 2;

    private $content;

    public function __construct($filename, $fileType = self::INI_FILE) {
        if (file_exists($filename))
            $this->parse($filename, $fileType);
        else
            exit('Config file is missing!');
    }

    private function parse($filename, $file_type) {
        if ($file_type === self::JSON_FILE)
            $this->content = json_decode(file_get_contents($filename));
        else if ($file_type === self::INI_FILE)
            $this->content = parse_ini_file($filename, true);
    }

    public function offsetSet($offset, $value) {
        
    }

    public function offsetExists($offset) {
        return isset($this->content[$offset]);
    }

    public function offsetUnset($offset) {
        unset($this->content[$offset]);
    }

    public function offsetGet($offset) {
        return isset($this->content[$offset]) ? $this->content[$offset] : null;
    }

}
