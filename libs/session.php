<?php if (!defined('IN_SITE')) die ('The request not found');

    session_start();
    
	// G�n session (SET)
    function session_set($key, $val){
        $_SESSION[$key] = $val;
    }
     
    // L?y session (GET)
    function session_get($key){
        return (isset($_SESSION[$key])) ? $_SESSION[$key] : false;
    }
     
    // X�a session (DELETE)
    function session_delete($key){
        if (isset($_SESSION[$key])){
            unset($_SESSION[$key]);
        }
    }
?>