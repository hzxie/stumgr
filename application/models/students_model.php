<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * The model is for the stumgr_users table in the database.
 *
 * The structure of stumgr_users:
 *     student_id			-- VARCHAR(16)	-- NOT NULL	--	[PRIMARY]
 *     student_name			-- VARCHAR(24)	-- NOT NULL
 *     grade				-- VARCHAR(4)	-- NOT NULL
 *     class				-- VARCHAR(2)	-- NOT NULL
 *     room					-- VARCHAR(8)	-- NOT NULL
 *     mobile				-- VARCHAR(12)
 *     email				-- VARCHAR(36)
 *
 * @author	Xie Haozhe <zjhzxhz@gmail.com>
 */
class Students_model extends CI_Model {
	/**
	 * The constructor of the Students_model class.
	 */
	public function __construct() 
	{
		parent::__construct(); 
		$this->load->database();
	}

	/**
	 * Get a record from the students table.
	 * @param  String $student_id - the student id of the student
	 * @return a profile of a certain student if the query is successful, 
	 *         or return false if the query is failed
	 */
	public function select($student_id)
	{
		$this->db->where('student_id', $student_id);
		
		$query = $this->db->get($this->db->dbprefix('students'));
		if ( $query->num_rows() > 0 ) {
			return $query->row_array();
		} else {
			return false;
		}
	}

	/**
	 * Insert a record to the students table.
	 * @param  Array $record - an array contains all fields
	 * @return true if the insert query is successful
	 */
	public function insert($record)
	{
		return $this->db->insert($this->db->dbprefix('students'), $record);
	}

	/**
	 * Update a record in the students table.
	 * @param  Array $record - an array contains some essential fields
	 * @return true if the update query is successful
	 */
	public function update($record)
	{
		$this->db->where('student_id', $record['student_id']);
		return $this->db->update($this->db->dbprefix('students'), $record);
	}

	/**
	 * Delete a record from the students table.
	 * @param  String $username - the username of the user
	 * @return true if the delete query is successful
	 */
	public function delete($student_id)
	{
		$this->db->where('student_id', $student_id);
		return $this->db->delete($this->db->dbprefix('students')); 
	}

	/**
	 * Get available grades to choose in the table.
	 * @return an array of available grades
	 */
	public function get_available_grades()
	{
		$this->db->distinct();
		$this->db->select('grade');
		
		$query = $this->db->get($this->db->dbprefix('students'));
		if ( $query->num_rows() > 0 ) {
			return $query->result_array();
		} else {
			return false;
		}
	}

	/**
	 * Get the students list in a certain class.
	 *
	 * This function is mainly used in getting students list in
	 * a certain class.
	 * 
	 * @param  int $grade - the grade of the students
	 * @param  int $class - the class of the students
	 * @return an array of students list
	 */
	public function get_students_list_by_class($grade, $class)
	{
		$this->db->select('student_id, student_name');

		$this->db->where('grade', $grade);
		$this->db->where('class', $class);
		
		$query = $this->db->get($this->db->dbprefix('students'));
		if ( $query->num_rows() > 0 ) {
			return $query->result_array();
		} else {
			return false;
		}
	}

	/**
	 * Get the students list in a certain grade.
	 *
	 * This function is mainly used in getting students list in
	 * a certain grade.
	 * 
	 * @param  int $grade - the grade of the students
	 * @return an array of students list
	 */
	public function get_students_list_by_grade($grade)
	{
		$this->db->select('student_id, student_name');

		$this->db->where('grade', $grade);
		
		$query = $this->db->get($this->db->dbprefix('students'));
		if ( $query->num_rows() > 0 ) {
			return $query->result_array();
		} else {
			return false;
		}
	}

	/**
	 * Get the students list in a certain grade.
	 *
	 * This function is mainly used for administrators to edit the 
	 * profile of the students in a certain grade.
	 * 
	 * @param  int $grade - the grade of the students
	 * @return an array of students list
	 */
	public function get_students_profile_list_by_grade($grade)
	{
		$this->db->where('grade', $grade);
		
		$query = $this->db->get($this->db->dbprefix('students'));
		if ( $query->num_rows() > 0 ) {
			return $query->result_array();
		} else {
			return false;
		}
	}

	public function get_rooms_list($grade)
	{
		$this->db->select('room');
		$this->db->where('grade', $grade);
		$this->db->distinct();
		$this->db->order_by('room');
		
		$query = $this->db->get($this->db->dbprefix('students'));
		if ( $query->num_rows() > 0 ) {
			return $query->result_array();
		} else {
			return false;
		}
	}
}

/* End of file students_model.php */
/* Location: ./application/models/students_model.php */