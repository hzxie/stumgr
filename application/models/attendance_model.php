<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * The model is for the stumgr_attendance table in the database.
 *
 * The structure of stumgr_attendance:
 *     school_year      -- INT(4)       -- NOT NULL
 *     semester         -- INT(2)       -- NOT NULL
 *     student_id       -- VARCHAR(10)  -- NOT NULL --  [PRIMARY]
 *     time             -- TIMESTAMP    -- NOT NULL --  [PRIMARY]
 *     rule_id          -- INT(2)       -- NOT NULL
 *
 * @author  Xie Haozhe <zjhzxhz@gmail.com>
 */
class Attendance_model extends CI_Model {
    /**
     * The constructor of the class
     */
    public function __construct() 
    {
        parent::__construct(); 
        $this->load->database();
    }

    /**
     * Insert attendance records to the attendance table.
     * @param  Array $record - an array contains attendance records
     * @return true if the query is successful
     */
    public function insert($record)
    {
        return $this->db->insert($this->db->dbprefix('attendance'), $record);
    }

    /**
     * Update attendance records from the attendance table.
     * @param  Array $record - an array contains attendance records
     * @return true if the query is successful
     */
    public function update($record)
    {
        $this->db->where('student_id', $record['student_id']);
        $this->db->where('time', $record['old_time']);

        $attendance_record = array(
                'student_id'    => $record['student_id'],
                'time'          => $record['new_time'],
                'rule_id'       => $record['rule_id']
            );
        return $this->db->update($this->db->dbprefix('attendance'), $attendance_record);
    }

    /**
     * Delete attendance records for a certain student from the attendance 
     * table.
     * @param  String $student_id - the student id of the student
     * @return true if the query is successful
     */
    public function delete($student_id)
    {
        $this->db->where('student_id', $student_id);
        return $this->db->delete($this->db->dbprefix('attendance')); 
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
        $query = $this->db->get($this->db->dbprefix('attendance'));
        if ( $query->num_rows() > 0 ) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    /**
     * Get attendance records from the attendance table.
     * @param  int    $school_year - the school year to query
     * @param  String $student_id - the student id of the student
     * @param  String $before_time - the start time to query
     * @return an array contains attendance records
     */
    public function get_attendance_records_by_students($school_year, $student_id, $before_time)
    {
        $attendance_table       = $this->db->dbprefix('attendance');
        $attendance_rules_table = $this->db->dbprefix('attendance_rules');

        $query = $this->db->query('SELECT * FROM '.$attendance_table.
                                  ' NATURAL JOIN '.$attendance_rules_table.
                                  ' WHERE school_year = ? AND student_id = ? AND'.
                                  ' time >= ?', array($school_year, $student_id, $before_time));
        if ( $query->num_rows() > 0 ) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    /**
     * Get attendance records for study monitors and sports monitors in a 
     * certain class.
     * @param  int    $school_year - the school year to query
     * @param  String $before_time - the start time to query
     * @param  String $grade - the grade of the students
     * @param  String $class - the class of the students
     * @param  String $type - the type of the attendance records to query
     * @return an array contains attendance records with query flags
     */
    public function get_attendance_records_by_class($school_year, $before_time, $grade, $class, $type)
    {
        $students_table         = $this->db->dbprefix('students');
        $attendance_table       = $this->db->dbprefix('attendance');
        $attendance_rules_table = $this->db->dbprefix('attendance_rules');

        $query = $this->db->query('SELECT student_id, student_name, time, description, additional_points'.
                                  ' FROM '.$attendance_table.
                                  ' NATURAL JOIN '.$students_table.
                                  ' NATURAL JOIN '.$attendance_rules_table.
                                  ' WHERE school_year = ? AND time >= ? AND grade = ? AND '.
                                  ' class = ? AND rule_group = ?', array($school_year, $before_time, $grade, $class, $type));
        if ( $query->num_rows() > 0 ) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    /**
     * Get attendance records for administrators in a certain grade.
     * @param  int    $school_year - the school year to query
     * @param  String $grade - the grade of the students
     * @param  String $before_time - the start time to query
     * @return an array contains attendance records with query flags
     */
    public function get_attendance_records_by_grade($school_year, $grade, $before_time)
    {
        $students_table         = $this->db->dbprefix('students');
        $attendance_table       = $this->db->dbprefix('attendance');
        $attendance_rules_table = $this->db->dbprefix('attendance_rules');

        $query = $this->db->query('SELECT student_id, student_name, time, description, additional_points'.
                                  ' FROM '.$attendance_table.
                                  ' NATURAL JOIN '.$students_table.
                                  ' NATURAL JOIN '.$attendance_rules_table.
                                  ' WHERE school_year = ? AND time >= ? AND grade = ?', array($school_year, $before_time, $grade));
        if ( $query->num_rows() > 0 ) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    /**
     * Delete an attendance record from the table.
     * @param  String    $student_id - the student id of the student
     * @param  TimeStamp $time - the time when the event happened
     * @return true if the query is successful
     */
    public function delete_record($student_id, $time)
    {
        $this->db->where('student_id', $student_id);
        $this->db->where('time', $time);
        return $this->db->delete($this->db->dbprefix('attendance')); 
    }
}

/* End of file attendance_model.php */
/* Location: ./application/models/attendance_model.php */