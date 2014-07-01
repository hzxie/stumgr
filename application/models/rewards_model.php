<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * The model is for the stumgr_rewards table in the database.
 *
 * The structure of stumgr_rewards:
 *     rewards_id		-- BIGINT(20)	-- NOT NULL 	--  [PRIMARY][AUTO_INCREMENT]
 *     school_year      -- INT(4)		-- NOT NULL
 *     student_id		-- VARCHAR(10)	-- NOT NULL
 *     reward_level_id	-- INT(4)		-- NOT NULL
 *     description		-- VARCHAR(255)	-- NOT NULL
 *     additional_score	-- FLOAT		-- NOT NULL
 *
 * @author  Xie Haozhe <zjhzxhz@gmail.com>
 */
class Rewards_model extends CI_Model {
    /**
     * The constructor of the class
     */
    public function __construct() 
    {
        parent::__construct(); 
        $this->load->database();
    }

    /**
     * Insert a record to the rewards table.
     * @param  Array $record - an array contains a reward record to insert
     * @return true if the query is successful
     */
    public function insert($record)
    {
    	return $this->db->insert($this->db->dbprefix('rewards'), $record);
    }

    /**
	 * Update a record in the rewards table.
	 * @param  Array $record - an array contains some essential fields
	 * @return true if the update query is successful
	 */
	public function update($record)
	{
		$this->db->where('reword_id', $record['reword_id']);
		return $this->db->update($this->db->dbprefix('rewards'), $record);
	}

	/**
	 * Delete a record from the rewards table.
	 * @param  String $reword_id - the id of the record
	 * @return true if the delete query is successful
	 */
	public function delete($reword_id)
	{
		$this->db->where('reword_id', $reword_id);
		return $this->db->delete($this->db->dbprefix('rewards')); 
	}

    public function get_all_available_years()
    {
        $this->db->distinct();
        $this->db->select('school_year');
        $this->db->order_by('school_year', 'desc');
        $query = $this->db->get($this->db->dbprefix('rewards'));
        if ( $query->num_rows() > 0 ) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    /**
     * Get available years to select from existing data.
     * @return an array contains all available years
     */
    public function get_available_years($student_id)
    {
        $this->db->distinct();
        $this->db->select('school_year');
        $this->db->where('student_id', $student_id);
        $this->db->order_by('school_year', 'desc');

        $query = $this->db->get($this->db->dbprefix('rewards'));
        if ( $query->num_rows() > 0 ) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    public function get_reward_levels()
    {
        $query = $this->db->get($this->db->dbprefix('reward_levels'));
        if ( $query->num_rows() > 0 ) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    public function get_reward_records_by_students($school_year, $student_id)
    {
    	$rewards_table  		= $this->db->dbprefix('rewards');
        $reward_levels_table	= $this->db->dbprefix('reward_levels');

        $query = $this->db->query('SELECT * '.
                                  'FROM '.$rewards_table.' A '.
                                  'NATURAL JOIN '.$reward_levels_table.' '.
                                  'WHERE school_year=? AND student_id=?', array($school_year, $student_id));
        if ( $query->num_rows() > 0 ) {
            return $query->result_array();
        } else {
            return false;
        }
    }
}

/* End of file rewards_model.php */
/* Location: ./application/models/rewards_model.php */