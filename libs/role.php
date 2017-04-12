<?php
	// H�m thi?t l?p l� d� dang nh?p
    function set_logged($username, $level){
        session_set('ss_user_token', array(
            'username' => $username,
            'level' => $level
        ));
    }
     
    // H�m thi?t l?p dang xu?t
    function set_logout(){
        session_delete('ss_user_token');
    }
     
    // H�m ki?m tra tr?ng th�i ngu?i d�ng d� dang h?p chua
    function is_logged(){
        $user = session_get('ss_user_token');
        return $user;
    }
     
    // H�m ki?m tra c� ph?i l� admin hay kh�ng
    function is_admin(){
        $user  = is_logged();
        if (!empty($user['level']) && $user['level'] == '1'){
            return true;
        }
        return false;
    }
    
    
    // L?y username ngu?i d�ng hi?n t?i
    function get_current_username(){
        $user  = is_logged();
        return isset($user['username']) ? $user['username'] : '';
    }
     
    // L?y level ngu?i d�ng hi?n t?i
    function get_current_level(){
        $user  = is_logged();
        return isset($user['level']) ? $user['level'] : '';
    }

?>