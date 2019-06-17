<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * The model is for the stumgr_assessment table in the database.
 *
 * The structure of stumgr_options:
 *     group_id		-- INT(4)		-- NOT NULL --	[PRIMARY][AUTO_INCREMENTAL]
 *     group_name	-- VARCHAR(64)	-- NOT NULL --	[UNIQUE]
 *     display_name	-- VARCHAR(64)	-- NOT NULL
 *
 * @author	Haozhe Xie <cshzxie@gmail.com>
 */
class User_groups_model extends CI_Model {
	/**
	 * The constructor of the class.
	 */
	public function __construct() 
	{
		parent::__construct(); 
		$this->load->database();
	}
	
	/**
	 * Get information of a certain user group.
	 * @param  int $group_id - the id of the user group
	 * @return the information of the user group
	 */
	public function select($group_id)
	{
		$this->db->where('group_id', $group_id);
		$query = $this->db->get( $this->db->dbprefix('user_groups') );
		if ( $query->num_rows() > 0 ) {
			return $query->row_array();
		} else {
			return false;
		}
	}

	/**
     * Get the id of a certain user group by its name.
     * @param  String $group_name - the name of the user group
     * @return the id of the user group
     */
	public function get_user_group_id($group_name)
	{
		$this->db->where('group_name', $group_name);
		$query = $this->db->get( $this->db->dbprefix('user_groups') );
		if ( $query->num_rows() > 0 ) {
			return $query->row_array();
		} else {
			return false;
		}
	}

	/**
	 * Get the list of user groups.
	 * @return an array of the list of user groups
	 */
	public function get_user_groups_list()
	{
		$query = $this->db->get( $this->db->dbprefix('user_groups') );
		if ( $query->num_rows() > 0 ) {
			return $query->result_array();
		} else {
			return false;
		}
	}
}

/* End of file user_groups_model.php */
/* Location: ./application/models/user_groups_model.php */