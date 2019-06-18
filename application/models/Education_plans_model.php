<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * The model is for the stumgr_education_plans table in the database.
 *
 * The structure of stumgr_education_plans:
 *     course_id        -- VARCHAR(8)       -- NOT NULL --  [PRIMARY]
 *     course_name      -- VARCHAR(64)      -- NOT NULL
 *     credits          -- FLOAT            -- NOT NULL
 *
 * @author  Haozhe Xie <cshzxie@gmail.com>
 */
class Education_plans_model extends CI_Model {
    /**
     * The constructor of the class
     */
    public function __construct() 
    {
        parent::__construct(); 
        $this->load->database();
    }

    public function insert($education_plan)
    {
        return $this->db->insert($this->db->dbprefix('education_plans'), $education_plan);
    }

    public function delete($education_plan)
    {
        $this->db->where('school_year', $education_plan['school_year']);
        $this->db->where('grade', $education_plan['grade']);
        $this->db->where('course_id', $education_plan['course_id']);

        return $this->db->delete($this->db->dbprefix('education_plans')); 
    }

    public function get_all_education_plans()
    {
        $courses_table          = $this->db->dbprefix('courses');
        $education_plans_table  = $this->db->dbprefix('education_plans');

        $query = $this->db->query('SELECT * '.
                                  'FROM '.$education_plans_table.' '.
                                  'NATURAL JOIN '.$courses_table);
        if ( $query->num_rows() > 0 ) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    public function get_education_plan($school_year, $grade)
    {
        $courses_table          = $this->db->dbprefix('courses');
        $education_plans_table  = $this->db->dbprefix('education_plans');

        $query = $this->db->query('SELECT * '.
                                  'FROM '.$education_plans_table.' '.
                                  'NATURAL JOIN '.$courses_table.' '.
                                  'WHERE school_year=? AND grade=?', array($school_year, $grade));
        if ( $query->num_rows() > 0 ) {
            return $query->result_array();
        } else {
            return false;
        }
    }
}

/* End of file education_plans_model.php */
/* Location: ./application/models/education_plans_model.php */