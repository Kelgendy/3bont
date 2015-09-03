<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Authentication {

	var $CI;
	
	/**
	 * Constructor
	 */
    function __construct()
    {
		// Obtain a reference to the ci super object
		$this->CI =& get_instance();
		
		$this->CI->load->library('session');
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Check user signin status
	 *
	 * @access public
	 * @return bool
	 */
	function is_signed_in()
	{
		return $this->CI->session->userdata('account_id') ? TRUE : FALSE;
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Sign user in
	 *
	 * @access public
	 * @param int $account_id
	 * @param bool $remember
	 * @return void
	 */
	function sign_in($account_id, $remember = FALSE)
	{
		$remember ? $this->CI->session->cookie_monster(TRUE) : $this->CI->session->cookie_monster(FALSE);
		
		$this->CI->session->set_userdata('account_id', $account_id);
		
		$this->CI->load->model('account/account_model');
		$this->CI->load->model('account/account_details_model');
		$this->CI->account_model->update_last_signed_in_datetime($account_id);
		
		$user = $this->CI->account_model->get_by_id($account_id);
		$user_details = $this->CI->account_details_model->get_by_account_id($account_id);
		$this->CI->session->set_userdata('account_username', $user->username);
		
		if(!empty($user_details->timezone))
			$this->CI->session->set_userdata('user_timezone', $user_details->timezone);
		
		$this->CI->session->set_userdata('account_admin', $user->admin);
		
		// Redirect signed in user with session redirect
		if ($redirect = $this->CI->session->userdata('sign_in_redirect')) 
		{
			$this->CI->session->unset_userdata('sign_in_redirect');
			redirect($redirect); 
		}
		// Redirect signed in user with GET continue
		elseif ($this->CI->input->get('continue')) 
		{
			redirect($this->CI->input->get('continue')); 
		}
		
		redirect('');
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Sign user out
	 *
	 * @access public
	 * @return void
	 */
	function sign_out()
	{
		$this->CI->session->unset_userdata('account_id');
		$this->CI->session->unset_userdata('account_admin');
		$this->CI->session->unset_userdata('account_username');
		$this->CI->session->unset_userdata('localization');
		$this->CI->session->unset_userdata('user_timezone');
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Check password validity
	 *
	 * @access public
	 * @param object $account
	 * @param string $password
	 * @return bool
	 */
	function check_password($password_hash, $password)
	{
		$this->CI->load->helper('account/phpass');
		
		$hasher = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
		
		return $hasher->CheckPassword($password, $password_hash) ? TRUE : FALSE;
	}
	
}


/* End of file Authentication.php */
/* Location: ./application/modules/account/libraries/Authentication.php */