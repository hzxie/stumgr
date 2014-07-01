<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * A class to handle and display help and support page.
 *
 * @date: August 4th, 2013
 * @author: Xie Haozhe <zjhzxhz@gmail.com>
 */
class Support extends CI_Controller {
	/**
	 * [$data description]
	 * @var array
	 */
	private $data = array();

	public function __construct()
	{
		parent::__construct();
		$session = $this->session->all_userdata();
		if ( isset($session['is_logged_in']) && $session['is_logged_in'] ) {
			$profile = array(
				'username'			=> $session['username'],
				'display_name'		=> $this->get_display_name($session['username'], $session['is_administrator']),
				'is_administrator'	=> $session['is_administrator']
			);
			$navigator_item = $this->get_navigator_item($session['is_administrator']);
			$this->data = array(
				'profile'			=> $profile,
				'navigator_item'	=> $navigator_item
			);
		}
	}

	/**
	 * Get the display name for a certain user.
	 * @param  String  $username - the username of the user
	 * @param  boolean $is_administrator - if the user is an administrator
	 * @return the display name for the user
	 */
	private function get_display_name($username, $is_administrator)
	{
		$this->load->library('lib_accounts');
		if ( $is_administrator ) {
			return $username;
		} else {
			$profile = $this->lib_accounts->get_profile($username);
			return $profile['student_name'];
		}
	}

	/**
	 * Display support/help.php page.
	 */
	public function help()
	{
		$this->load->view('support/help.php', $this->data);
	}

	/**
	 * Display support/about.php page.
	 */
	public function about()
	{
		$this->load->view('support/about.php', $this->data);
	}
	
	/**
	 * Get change log text from CHANGELOG
	 */
	private function get_change_log()
	{
		
	}

	/**
	 * Get navigator item for a certain user.
	 * @param  boolean $is_administrator - if the user is an administrator
	 * @return [type]                   [description]
	 */
	private function get_navigator_item( $is_administrator ) 
	{
		$navigator_item = array();
		if ( $is_administrator ) {
			// ...
		} else {
			$navigator_item = array(
				'欢迎' 			=> base_url().'home#welcome',
				'账户'	 		=> base_url().'home#profile'
			);
		}

		return $navigator_item;
	}
}

/* End of file support.php */
/* Location: ./application/controllers/support.php */