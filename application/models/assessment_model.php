<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * The model is for the stumgr_assessment table in the database.
 *
 * The structure of stumgr_assessment:
 *     school_year          -- INT(4)       -- NOT NULL --  [PRIMARY]
 *     student_id           -- VARCHAR(10)  -- NOT NULL --  [PRIMARY]
 *     is_participated      -- BOOLEAN      -- NOT NULL
 *     moral_excellent      -- INT(4)       -- NOT NULL
 *     moral_good           -- INT(4)       -- NOT NULL
 *     moral_medium         -- INT(4)       -- NOT NULL
 *     moral_poor           -- INT(4)       -- NOT NULL
 *     strength_excellent   -- INT(4)       -- NOT NULL
 *     strength_good        -- INT(4)       -- NOT NULL
 *     strength_medium      -- INT(4)       -- NOT NULL
 *     strength_poor        -- INT(4)       -- NOT NULL
 *     ability_excellent    -- INT(4)       -- NOT NULL
 *     ability_good         -- INT(4)       -- NOT NULL
 *     ability_medium       -- INT(4)       -- NOT NULL
 *     ability_poor         -- INT(4)       -- NOT NULL
 *
 * @author  Xie Haozhe <zjhzxhz@gmail.com>
 */
class Assessment_model extends CI_Model {
    /**
     * The constructor of the class.
     */
    public function __construct() 
    {
        parent::__construct(); 
        $this->load->database();
    }
    
    /**
     * 
     * This function is mainly used for students to query their own scores
     * of peer assessment.
     * 
     * @param  int $school_year - the year when the peer assessment carried on
     * @param  String $student_id - the student id of the student
     * @return a record if the query is successful, or return false if 
     *         the query is failed
     */
    public function select($school_year, $student_id)
    {
        $this->db->where('school_year', $school_year);
        $this->db->where('student_id', $student_id);
        $query = $this->db->get( $this->db->dbprefix('assessment') );
        if ( $query->num_rows() > 0 ) {
            return $query->row_array();
        } else {
            return false;
        }
    }

    public function get_available_years($student_id)
    {
        $this->db->distinct();
        $this->db->select('school_year');
        $this->db->where('student_id', $student_id);
        $this->db->order_by('school_year', 'asc');

        $query = $this->db->get($this->db->dbprefix('assessment'));
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
    public function get_all_available_years()
    {
        $this->db->distinct();
        $this->db->select('school_year');
        $this->db->order_by('school_year', 'asc');
        $query = $this->db->get($this->db->dbprefix('assessment'));
        if ( $query->num_rows() > 0 ) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    public function get_assessment_records_by_student($school_year, $student_id)
    {
        $this->db->where('school_year', $school_year);
        $this->db->where('student_id', $student_id);
        $query = $this->db->get($this->db->dbprefix('assessment'));

        if ( $query->num_rows() > 0 ) {
            return $query->row_array();
        } else {
            return false;
        }
    }

    public function get_assessment_records_by_grade($school_year, $grade)
    {
        $assessment_table   = $this->db->dbprefix('assessment');
        $students_table     = $this->db->dbprefix('students');

        $query = $this->db->query('SELECT * '.
                                  'FROM '.$assessment_table.' '.
                                  'NATURAL JOIN '.$students_table.' '.
                                  'WHERE school_year=? AND grade=?', array($school_year, $grade));
        if ( $query->num_rows() > 0 ) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    /**
     * Insert peer assessment data into the table.
     * 
     * The function is mainly used for initializing the peer assessment 
     * system when the system is actived.
     * 
     * @param  Array $record - contains two keys[school_year, student_id] most 
     *                         of the time.
     * @return true if the query is successful
     */
    public function insert($record)
    {
        return $this->db->insert($this->db->dbprefix('assessment'), $record);
    }

    /**
     * Update peer assessment data in the table.
     * 
     * The function is mainly used for students submit their votes.
     * 
     * @param  Array $record - contains peer assessment data
     * @return true if the query is successful
     */
    public function update($record)
    {
        $this->db->where('school_year', $record['school_year']);
        $this->db->where('student_id', $record['student_id']);
        return $this->db->update($this->db->dbprefix('assessment'), $record);
    }

    /**
     * Delete records of a certain student in the table.
     *
     * The function is mainly used for deleting an account, and all
     * data of the account should be deleted.
     * 
     * @param  String $student_id - the student id of the student
     * @return true if the query is successful
     */
    public function delete($student_id)
    {
        $this->db->where('student_id', $student_id);
        return $this->db->delete($this->db->dbprefix('assessment')); 
    }
}

/* End of file assessment_model.php */
/* Location: ./application/models/assessment_model.php */