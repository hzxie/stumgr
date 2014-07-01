<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * The class is used to handle user signin or 
 * signup request.
 *
 * @author: Xie Haozhe <zjhzxhz@gmail.com>
 */
class Accounts extends CI_Controller {
	/**
	 * The contructor of the class.
	 */
	public function __construct()
    {
        parent::__construct();
        $this->load->library('lib_accounts');

        if ( !$this->is_browser_compatible() ) {
        	redirect(base_url().'../not-supported/');
        }
    }

    /**
     * Verify if the broswer is compatible.
     * @return true if the browser is compatible
     */
    private function is_browser_compatible()
    {
    	$this->load->library('user_agent');
    	if( $this->agent->browser() == 'Internet Explorer' && 
    		in_array($this->agent->version(), array('6.0')) ) {
    		return false;
    	}
    	return true;
    }
	
	/**
	 * Display signin.php page for user to sign in.
	 */
	public function index()
	{
		$session = $this->session->all_userdata();
		if ( $this->lib_accounts->auto_sign_in($session) ) {
			redirect($this->lib_accounts->get_redirect_url($session['is_administrator']));
		} else {
			$this->session->sess_destroy();
			$this->load->view('accounts/signin.php');
		}
	}

	/**
	 * Handle user's sign in request.
	 */
	public function signin()
	{
		$username = $this->input->post('username');
		$password = $this->input->post('password');
		$allow_auto_sign_in = $this->input->post('persistent-cookie');
	
		$result = $this->lib_accounts->sign_in($username, $password);

		if ( $result['is_passed'] ) {
			$is_administrator = $result['is_administrator'];
			$last_time_signin = $result['last_time_signin'];
			$this->lib_accounts->set_user_session($is_administrator, $username, 
												  $allow_auto_sign_in, $last_time_signin);
			redirect($this->lib_accounts->get_redirect_url($is_administrator));
		} else {
			$result['username'] = $username;
			$this->load->view('accounts/signin.php', $result);
		}
	}
	
	/**
	 * Handle user's sign out request.
	 */
	public function signout()
	{
		$this->session->sess_destroy();
		redirect(base_url(). 'accounts');
	}
}

/* End of file accounts.php */
/* Location: ./application/controllers/accounts.php */