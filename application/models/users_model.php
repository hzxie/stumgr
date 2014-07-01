<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * The model is for the stumgr_users table in the database.
 *
 * The structure of stumgr_users:
 *     username						-- VARCHAR(16)	-- NOT NULL	--	[PRIMARY]
 *     password						-- VARCHAR(32)	-- NOT NULL
 *     is_administrator				-- BOOLEAN		-- NOT NULL
 *     last_time_signin				-- TIMESTAMP	-- NOT NULL
 *     last_time_change_password	-- TIMESTAMP	-- NOT NULL
 *
 * @author	Xie Haozhe <zjhzxhz@gmail.com>
 */
class Users_model extends CI_Model {
	/**
	 * The constructor of the Users_model class.
	 */
	public function __construct() 
	{
		parent::__construct(); 
		$this->load->database();
	}

	/**
	 * Get a record from the users table.
	 * @param  String $username - the username of the user
	 * @return a record if the query is successful, or return false if 
	 *         the query is failed
	 */
	public function select($username)
	{
		$this->db->where('username', $username);
		$query = $this->db->get($this->db->dbprefix('users'));
		if ( $query->num_rows() > 0 ) {
			return $query->row_array();
		} else {
			return false;
		}
	}

	/**
	 * Insert a record to the users table.
	 * @param  Array $record - an array contains all fields
	 * @return true if the insert query is successful
	 */
	public function insert($record)
	{
		$record['password'] = md5($record['password']);
		return $this->db->insert($this->db->dbprefix('users'), $record);
	}

	/**
	 * Update a record in the users table.
	 * @param  Array $record - an array contains some essential fields
	 * @return true if the update query is successful
	 */
	public function update($record)
	{
		$record['password'] = md5($record['password']);
		$this->db->where('username', $record['username']);
		return $this->db->update($this->db->dbprefix('users'), $record);
	}

	/**
	 * Delete a record from the users table.
	 * @param  String $username - the username of the user
	 * @return true if the delete query is successful
	 */
	public function delete($username)
	{
		$this->db->where('username', $username);
		return $this->db->delete($this->db->dbprefix('users')); 
	}

	/**
	 * Handles users' sign in requrests.
	 * The function query the users table and verify if the username and 
	 * password is correct.
	 * 
	 * @param  String $username - the username of the user
	 * @param  String $password - the password of the user
	 * @return the information of the user if the query is successful, or 
	 *         return false if the query is failed
	 */
	public function signin($username, $password)
	{
		$this->db->where('username', $username);
		$this->db->where('password', $password);
		
		$query = $this->db->get($this->db->dbprefix('users'));
		if ( $query->num_rows() > 0 ) {
			$query = $query->row_array();
			$this->update_time_signin($username);
			return $query;
		} else {
			return false;
		}
	}

	/**
	 * Update the last_time_signin value in the users table.
	 * @param   $username - the username of the user
	 */
	private function update_time_signin($username)
	{
		$time = new DateTime('now', new DateTimeZone('Asia/Shanghai'));
		$login_session = array(
				'username'			=> $username,
				'last_time_signin'	=> $time->format('Y-m-d H:i:s')
			);
		$this->db->where('username', $username);
		$this->db->update($this->db->dbprefix('users'), $login_session); 
	}

	/**
	 * Handles users' change password requests.
	 * @param  String $username - the username of the user
	 * @param  String $old_password - the password the user used before
	 * @param  String $new_password - the new password the user will use
	 * @return true if the operation is successful.
	 */
	public function change_password($username, $old_password, $new_password, $is_administrator = false)
	{
		$this->db->where('username', $username);
		$this->db->where('password', md5($old_password));

		$query = $this->db->get($this->db->dbprefix('users'));
		if ( $query->num_rows() > 0 ) {
			$time = new DateTime('now', new DateTimeZone('Asia/Shanghai'));
			$new_account_record = array(
					'username'					=> $username,
					'password'					=> $new_password,
					'last_time_change_password'	=> $time->format('Y-m-d H:i:s')
				);
			return $this->update($new_account_record);
		} else {
			// Incorrect username or password.
			return false;
		}
	}
}

/* End of file users_model.php */
/* Location: ./application/models/users_model.php */