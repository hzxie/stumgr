<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * The model is for the stumgr_courses table in the database.
 *
 * The structure of stumgr_courses:
 *     course_id        -- VARCHAR(8)       -- NOT NULL --  [PRIMARY]
 *     course_name      -- VARCHAR(64)      -- NOT NULL
 *     credits          -- FLOAT            -- NOT NULL
 *
 * @author  Haozhe Xie <cshzxie@gmail.com>
 */
class Courses_model extends CI_Model {
    /**
     * The constructor of the class
     */
    public function __construct() 
    {
        parent::__construct(); 
        $this->load->database();
    }

    public function get_all_courses() 
    {
        $query = $this->db->get($this->db->dbprefix('courses'));
        if ( $query->num_rows() > 0 ) {
            return $query->result_array();
        } else {
            return false;
        }
    }
}

/* End of file courses_model.php */
/* Location: ./application/models/courses_model.php */