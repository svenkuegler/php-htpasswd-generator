<?php

/**
 * @package HtpasswdGenerator
 * @author Sven Kuegler <sven.kuegler@gmail.com>
 * @see https://github.com/svenkuegler/php-htpasswd-generator
 */
class HtpasswdGenerator {
    
    const MESSAGE_ERROR = "error";
    const MESSAGE_SUCCESS = "success";
    const MESSAGE_NOTICE = "notice";
    
    /**
     * .htpasswd File
     * @var string
     */
    private $htpasswdFile = ".htpasswd";
    
    /**
     * Users in File
     * @var array
     */
    private $users = array();
    
    /**
     * Messages
     * @var array 
     */
    private $_messages = array();
    
    /**
     * Construct
     * @param string|null $htpasswdFile
     */
    function __construct($htpasswdFile=null) {
        if(!is_null($htpasswdFile)) {
            $this->htpasswdFile = $htpasswdFile;
        }
        
        if(file_exists($this->htpasswdFile) && !is_writable($this->htpasswdFile)) {
            $this->addMessage($this->htpasswdFile . _(" is not writeable"));
        }
    }
    
    /**
     * 
     * @param type $username
     * @param type $password
     * @return \HtpasswdGenerator
     */
    public function add($username, $password) {
        $this->loadFile();
        $this->setUser($this->cleanUp($username), $this->cryptApr1Md5($password));
        $this->saveFile();
        return $this;
    }
    
    /**
     * 
     * @param type $username
     * @return \HtpasswdGenerator
     */
    public function delete($username) {
        $this->loadFile();
        if(array_key_exists($this->cleanUp($username), $this->getUsers())) {
            $oldusers = $this->getUsers();
            $this->users = array();
            foreach ($oldusers as $user => $passwd) {
                if($user != $this->cleanUp($username)) {
                    $this->setUser($user, $passwd);
                }
            }
            $this->saveFile();
            $this->addMessage(_("User deleted"), HtpasswdGenerator::MESSAGE_SUCCESS);
        } else {
            $this->addMessage(_("User unknown!"), HtpasswdGenerator::MESSAGE_NOTICE);
        }
        return $this;
    }
    
    /**
     * User Password validation
     * 
     * @param string $username
     * @param string $password
     * @return boolean
     */
    public function isValid($username, $password) {
        $this->loadFile();
        
        foreach ($this->getUsers() as $user => $passwd) {
            if($user == $this->cleanUp($username)) {
                if($passwd == $this->cryptApr1Md5($password, $passwd)) {
                    return true;
                } else {
                    return false;
                }
            }
        }
        
        return false;
    }
    
    /**
     * Getter for Users Array
     * 
     * @return array
     */
    public function getUsers() {
        return $this->users;
    }
    
    /**
     * Setter for Users Array
     * 
     * @param string $username
     * @param string $password
     * @return array
     */
    public function setUser($username, $password) {
        $this->users[$username] = $password;
        return $this->users;
    }
    
    /**
     * Clear Users Array
     * 
     * @return array
     */
    public function clearUsers() {
        $this->users = array();
        return $this->users;
    }


    /** ************** private ******************************** */
   
    
    /**
     * Cleanup Variable
     * 
     * @param string $str
     * @return string
     */
    private function cleanUp($str) {
        $out = trim($str);
        return $out;
    }

    /**
     * load file and fill users array
     * 
     * @return \HtpasswdGenerator
     */
    private function loadFile() {
        if(!file_exists($this->htpasswdFile)) {
            $this->addMessage(_(".htpasswd File not found"), HtpasswdGenerator::MESSAGE_NOTICE);
            return $this;
        } else {
            if(is_readable($this->htpasswdFile)) {
                foreach(file($this->htpasswdFile) as $row) {
                    $e = explode(":", $row);
                    $this->setUser($e[0], preg_replace("#\r\n#", "", $e[1]));
                }
            } else {
                $this->addMessage(_("File is not readable"));
            }
            return $this;
        }
    }
    
    /**
     * save results to file
     */
    private function saveFile() {
        $result = "";
        if((file_exists($this->htpasswdFile) && is_writeable($this->htpasswdFile)) || !file_exists($this->htpasswdFile)) {
            foreach ($this->getUsers() as $username => $password) {
                $result .= $username . ":" . $password . "\r\n";
            }
            file_put_contents($this->htpasswdFile, $result);
        } else {
            $this->addMessage(_("File is not writeable"), HtpasswdGenerator::MESSAGE_ERROR);
        }
    }
    
    /**
     * Add a Message
     * 
     * @param string $message
     * @param string $type
     * @return \HtpasswdGenerator
     */
    private function addMessage($message, $type=HtpasswdGenerator::MESSAGE_ERROR) {
        $this->_messages[$type][] = $message;
        return $this;
    }
    
    /**
     * Generate Md5 Password
     *
     * @param string $plainpasswd
     * @param string|null $salt
     * @return string
     */
    private function cryptApr1Md5($plainpasswd, $salt=null) {
        if ($salt === null) {
            $salt = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz0123456789"), 0, 8);
        } else {
            if (substr($salt, 0, 6) == '$apr1$') {
                $salt = substr($salt, 6, 8);
            } else {
                $salt = substr($salt, 0, 8);
            }
        }
        $len  = strlen($plainpasswd);
        $text = $plainpasswd . '$apr1$' . $salt;
        $bin  = pack("H32", md5($plainpasswd . $salt . $plainpasswd));
        for ($i = $len; $i > 0; $i -= 16) {
            $text .= substr($bin, 0, min(16, $i));
        }
        for ($i = $len; $i > 0; $i >>= 1) {
            $text .= ($i & 1) ? chr(0) : $plainpasswd{0};
        }
        $bin = pack("H32", md5($text));
        for ($i = 0; $i < 1000; $i++) {
            $new = ($i & 1) ? $plainpasswd : $bin;
            if ($i % 3) $new .= $salt;
            if ($i % 7) $new .= $plainpasswd;
            $new .= ($i & 1) ? $bin : $plainpasswd;
            $bin = pack("H32", md5($new));
        }
        $tmp = '';
        for ($i = 0; $i < 5; $i++) {
            $k = $i + 6;
            $j = $i + 12;
            if ($j == 16) $j = 5;
            $tmp = $bin[$i] . $bin[$k] . $bin[$j] . $tmp;
        }
        $tmp = chr(0) . chr(0) . $bin[11] . $tmp;
        $tmp = strtr(strrev(substr(base64_encode($tmp), 2)),
            "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/",
            "./0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz");
        return "$" . "apr1" . "$" . $salt . "$" . $tmp;
    }
}