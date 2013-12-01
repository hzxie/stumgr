<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * The model is for the stumgr_scores table in the database.
 *
 * The structure of stumgr_scores:
 *     school_year      -- INT(4)       -- NOT NULL
 *
 * @author  Xie Haozhe <zjhzxhz@gmail.com>
 */
class Scores_model extends CI_Model {
    /**
     * The constructor of the class
     */
    public function __construct() 
    {
        parent::__construct(); 
        $this->load->database();
    }

    public function select($school_year, $semester, $student_id)
    {
        $scores_table  = $this->db->dbprefix('scores');
        $courses_table = $this->db->dbprefix('courses');

        $query = $this->db->query('SELECT *, (1 + (SELECT COUNT(*) '.
                                                  'FROM '.$scores_table.' B '.
                                                  'WHERE B.course_id = A.course_id AND '.
                                                  'B.school_year = A.school_year AND '.
                                                  'B.semester = A.semester AND '.
                                                  'B.final_score > A.final_score)) AS ranking,'.
                                            '(SELECT COUNT(*) '.
                                             'FROM `stumgr_scores` B '.
                                             'WHERE B.course_id = A.course_id AND '.
                                             'B.school_year = A.school_year AND '.
                                             'B.semester = A.semester ) AS total '.
                                  'FROM '.$scores_table.' A '.
                                  'NATURAL JOIN '.$courses_table.' '.
                                  'WHERE school_year=? AND semester=? AND student_id=?', array($school_year, $semester, $student_id));
        if ( $query->num_rows() > 0 ) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    /**
     * Insert a record to the scores table.
     * @param  Array $record - an array contains a score record to insert
     * @return true if the query is successful
     */
    public function insert($record)
    {
    	return $this->db->insert($this->db->dbprefix('scores'), $record);
    }

    public function get_all_available_years()
    {
        $this->db->distinct();
        $this->db->select('school_year');
        $this->db->order_by('school_year', 'asc');
        $query = $this->db->get($this->db->dbprefix('scores'));
        if ( $query->num_rows() > 0 ) {
            return $query->result_array();
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

        $query = $this->db->get($this->db->dbprefix('scores'));
        if ( $query->num_rows() > 0 ) {
            return $query->result_array();
        } else {
            return false;
        }
    }
}

/* End of file scores_model.php */
/* Location: ./application/models/scores_model.php */