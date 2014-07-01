<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * The model is for the stumgr_courses table in the database.
 *
 * The structure of stumgr_courses:
 *     school_year      -- INT(4)       -- NOT NULL
 *
 * @author  Xie Haozhe <zjhzxhz@gmail.com>
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
}

/* End of file courses_model.php */
/* Location: ./application/models/courses_model.php */