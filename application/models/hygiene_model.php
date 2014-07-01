<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * The model is for the stumgr_hygiene table in the database.
 *
 * The structure of stumgr_attendance:
 *     school_year      -- INT(4)       -- NOT NULL --  [PRIMARY]
 *     semester         -- INT(2)       -- NOT NULL --  [PRIMARY]
 *     student_id       -- VARCHAR(10)  -- NOT NULL --  [PRIMARY]
 *     week             -- INT(2)       -- NOT NULL --  [PRIMARY]
 *     room             -- VARCHAR(8)   -- NOT NULL
 *     score            -- INT(4)
 *
 * @author  Xie Haozhe <zjhzxhz@gmail.com>
 */
class Hygiene_model extends CI_Model {
    /**
     * The constructor of the class
     */
    public function __construct() 
    {
        parent::__construct(); 
        $this->load->database();
    }

    /**
     * Get available years to select from existing data.
     * @return an array contains all available years
     */
    public function get_available_years()
    {
        $this->db->distinct();
        $this->db->select('school_year');
        $this->db->order_by('school_year', 'asc');
        $query = $this->db->get($this->db->dbprefix('hygiene'));
        if ( $query->num_rows() > 0 ) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    public function get_hygiene_records_by_students($school_year, $semester, $student_id)
    {
        $this->db->where('school_year', $school_year);
        $this->db->where('semester', $semester);
        $this->db->where('student_id', $student_id);
        $query = $this->db->get($this->db->dbprefix('hygiene'));

        if ( $query->num_rows() > 0 ) {
            return $query->result_array();
        } else {
            return false;
        }
    }
}

/* End of file hygiene_model.php */
/* Location: ./application/models/hygiene_model.php */