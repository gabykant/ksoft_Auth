<?php 

    if ( ! defined('BASEPATH')) 
        exit('No direct script access allowed'); 
    
    define('ISACTIVATED', 1);
    define('ISNOTACTIVATED', 0);

    class Ksoft_Auth {
        
        private  $errors = array();
        
        function __construct() {
            $this->ci_global = & get_instance();
        }
        
        function login($array) {

            if(!is_null($user_info = $this->ci_global->users_model->get_user_info($array))){
                
                if($user_info->isactive === ISNOTACTIVATED) {   //User account not activated

                    $this->ci_global->session->set_userdata("user_not_activated");
                }else {
                    if($this->isPasswordMatches($array['password'], $user_info->password)) { // If success
                        $userSession = array(
                            "user_id" => $user_info->id,
                            "user_username" => $user_info->username
                        );
                        $this->ci_global->session->set_userdata($userSession);
                        return TRUE;
                    } else {
                        $this->ci_global->session->set_userdata("pwd_not_match");
                    }
                }
            }
            return FALSE;
        }
        
        function logout() {
            $this->ci_global->session->unset_userdata(array('user_id'=>'','user_username'=>'','user_not_activated'=>'', 'pwd_not_match'=>''));
            $this->ci_global->session->sess_destry();
        }
        
        function isPasswordMatches($incoming_password, $db_password) {
            if($incoming_password === $db_password) return TRUE;
            return FALSE;
        }
        
        function isLogged() {
            return $this->ci_global->session->userdata('islogged');
        }
        
        function getDisplayErrors() {
            return $this->errors;
        }
    }
 
?>
