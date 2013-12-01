<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * The model is for the stumgr_users table in the database.
 *
 * The structure of stumgr_sessions:
 *     sessions_id			-- VARCHAR(40)	-- NOT NULL --	[PRIMARY]
 *     ip_address			-- VARCHAR(45)	-- NOT NULL
 *     user_agent			-- VARCHAR(120)	-- NOT NULL
 *     last_activity		-- INT(10)		-- NOT NULL
 *     user_data			-- TEXT			-- NOT NULL
 *
 * @author	Xie Haozhe <zjhzxhz@gmail.com>
 */
class Sessions_model extends CI_Model {
	/**
	 * The constructor of the class
	 */
	public function __construct() 
	{
		parent::__construct(); 
		$this->load->database();
	}

	/**
	 * Verify if a session is valid.
	 * @param  String  $session_id [description]
	 * @return boolean             [description]
	 */
	public function is_valid($session_id)
	{
		$this->db->where('session_id', $session_id);

		$query = $this->db->get( $this->db->dbprefix('sessions') );
		if ( $query->num_rows() > 0 ) {
			return true;
		} else {
			return false;
		}
	}
}

/* End of file sessions_model.php */
/* Location: ./application/models/sessions_model.php */