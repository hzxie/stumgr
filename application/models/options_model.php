<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * The model is for the stumgr_options table in the database.
 *
 * The structure of stumgr_options:
 *     option_id			-- BIGINT(20)	-- NOT NULL --	[PRIMARY][AUTO_INCREMENT]
 *     option_name			-- VARCHAR(64)	-- NOT NULL --	[UNIQUE]
 *     options_value		-- VARCHAR(64)	-- NOT NULL
 *
 * @author	Haozhe Xie <cshzxie@gmail.com>
 */
class Options_model extends CI_Model {
	/**
	 * The constructor of the class
	 */
	public function __construct() 
	{
		parent::__construct(); 
		$this->load->database();
	}

	/**
	 * Overload function handler.
	 * @param String $name - the name of the function to call
	 * @param Array  $args - the arguments passed to the function
	 */
	public function __call($name, $args)
	{
		if( $name == 'select' ) {  
			switch( count($args) ) {
				case 0:
					return $this->get_all_options();
				case 1: 
					return $this->get_option($args[0]);
				default:
					break;  
			}  
		}
	}

	/**
	 * Get all options in the options table.
	 * @return an array which contains the value of the option if the 
	 *         query is successful, or return false if the query is 
	 *         failed
	 */
	public function get_all_options()
	{
		$query = $this->db->get( $this->db->dbprefix('options') );
		if ( $query->num_rows() > 0 ) {
			return $query->result_array();
		} else {
			return false;
		}
	}

	/**
	 * Get a certain option by its name.
	 * @param  String $option_name - the name of the option
	 * @return an array which contains the value of the option if the 
	 *         query is successful, or return false if the query is 
	 *         failed
	 */
	public function get_option($option_name)
	{
		$this->db->where('option_name', $option_name);
		$query = $this->db->get( $this->db->dbprefix('options') );
		if ( $query->num_rows() > 0 ) {
			return $query->row_array();
		} else {
			return false;
		}
	}

	/**
	 * Update a value of a certain option.
	 * @param  String $option_name  - the name of the option
	 * @param  String $option_value - the value of the option
	 * @return true if the query is successful
	 */
	public function update($option_name, $option_value)
	{
		$this->db->where('option_name', $option_name);
		$option = array(
				'option_name'		=>	$option_name,
				'option_value'		=>	$option_value
			);
		return $this->db->update($this->db->dbprefix('options'), $option);
	}
}

/* End of file options_model.php */
/* Location: ./application/models/options_model.php */