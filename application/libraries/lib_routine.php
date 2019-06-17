<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * The class handles all requests about routine.
 * @author: Xie Haozhe <zjhzxhz@gmail.com>
 */
class Lib_routine {
    /**
     * @var A instance of CodeIgniter.
     */
    private $__CI;
    
    /**
     * The contructor of the Routine Library class.
     */
    public function __construct() 
    {
        $this->__CI =& get_instance();
        $this->__CI->load->model('Attendance_model');
        $this->__CI->load->model('Attendance_rules_model');
        $this->__CI->load->model('Hygiene_model');
        $this->__CI->load->model('Students_model');

        $this->__CI->load->library('lib_utils');
    }

    /**
     * Get the current school year in school.
     * @return the current school year in school
     */
    public function get_current_school_year()
    {
        $current_month = date('n');

        if ( $current_month >= 8 ) {
            return date('Y');
        } else {
            return (date('Y') - 1);
        }
    }

    /**
     * Get the current semester in school.
     * @return the current semester in school
     */
    public function get_current_semester()
    {
        $current_month = date('n');

        if ( $current_month >= 8 || $current_month <= 1 ) {
            return 1;
        } else {
            return 2;
        }
    }

    /**
     * Get available years to select from existing data.
     * 
     * The function is mainly used for students to query their attendance
     * records from the database. So the available years should not earlier
     * than when they attend university.
     *
     * @param String  student_id - the student id of the student
     * @return an array contains all available years
     */
    public function get_available_years_for_attendance($student_id)
    {
        $available_years     = $this->__CI->Attendance_model->get_available_years();
        return $this->get_available_years($student_id, $available_years);
    }

    /**
     * Get available years to select from existing data.
     * 
     * The function is mainly used for students to query their hygiene records 
     * from the database. So the available years should not earlier than when 
     * they attend university.
     *
     * @param String  student_id - the student id of the student
     * @return an array contains all available years
     */
    public function get_available_years_for_hygiene($student_id)
    {
        $available_years     = $this->__CI->Hygiene_model->get_available_years();
        return $this->get_available_years($student_id, $available_years);
    }

    /**
     * Get available years to select from existing data.
     * @param  String $student_id - the student id of the student
     * @param  Array  $available_years - available years to select
     * @return an array contains all available years
     */
    private function get_available_years($student_id, &$available_years)
    {
        $current_size        = is_array($available_years) ? count($available_years) : 0;
        $current_school_year = $this->get_current_school_year();
        $year_attend_school  = substr($student_id, 0, 4);
        $start_index = 0;

        if ( !$this->__CI->lib_utils->in_array($available_years, 'school_year', $current_school_year) ) {
            $available_years[$current_size]['school_year'] = $current_school_year;
        }
        foreach ( $available_years as &$available_year ) {
            if ( $available_year['school_year'] < $year_attend_school ) {
                ++ $start_index;
            }
        }
        return array_reverse(array_slice($available_years, $start_index));
    }

    /**
     * Get available years to select from existing data.
     *
     * The function is mainly used for administrators to get all available
     * years for attendance records in the database.
     * 
     * @return an array contains all available years
     */
    public function get_all_available_years_for_attendance()
    {
        $available_years     = $this->__CI->Attendance_model->get_available_years();
        return $this->get_all_available_years($available_years);        
    }

    /**
     * Get available years to select from existing data.
     *
     * The function is mainly used for administrators to get all available
     * years for hygiene records in the database.
     * 
     * @return an array contains all available years
     */
    public function get_all_available_years_for_hygiene()
    {
        $available_years     = $this->__CI->Attendance_model->get_available_years();
        return $this->get_all_available_years($available_years);
    }

    /**
     * Get all available years to select from existing data.
     * @param  Array  $available_years - available years to select
     * @return an array contains all available years
     */
    private function get_all_available_years($available_years)
    {
        $current_size        = count($available_years);
        $current_school_year = $this->get_current_school_year();

        if ( !$this->__CI->lib_utils->in_array($available_years, 'school_year', $current_school_year) ) {
            $available_years[$current_size]['school_year'] = $current_school_year;
        }
        return array_reverse($available_years);
    }

    /**
     * Get available grades to select from existing data.
     * @return an array contains all available grades
     */
    public function get_available_grades()
    {
        $available_grades       = $this->__CI->Students_model->get_available_grades();
        $current_size           = count($available_grades);
        $current_school_year    = $this->get_current_school_year();

        if ( !$this->__CI->lib_utils->in_array($available_grades, 'grade', $current_school_year) ) {
            $available_grades[$current_size]['grade'] = $current_school_year;
        }
        return array_reverse($available_grades);
    }

    /**
     * Handle students' getting attendance records requests.
     * @param  int    $school_year - the school year to query
     * @param  String $student_id - the student id of the student
     * @param  String $student_name - the student name of the student
     * @param  String $time - the value is one of ( 'a-week', 'two-weeks'
     *         'a-month', 'all' ), stands for which record to query
     * @return an array contains attendance records with query flags
     */
    public function get_attendance_records_by_students($school_year, $student_id, $student_name, $time)
    {
        $before_time = $this->get_before_time($time);
        $attendance_records = $this->__CI->Attendance_model->get_attendance_records_by_students($school_year, $student_id, $before_time);
        $attendance_records = $this->__CI->lib_utils->add_column_to_array($attendance_records, 
                                                                          'student_name', $student_name);
        return $attendance_records;
    }

    public function get_hygiene_records_by_students($school_year, $semester, $student_id)
    {
        return $this->__CI->Hygiene_model->get_hygiene_records_by_students($school_year, $semester, $student_id);
    }

    /**
     * Get attendance records for study monitors and sports monitors in a 
     * certain class.
     * @param  int    $school_year - the school year to query
     * @param  String $time - the value is one of ( 'a-week', 'two-weeks'
     *         'a-month', 'all' ), stands for which record to query
     * @param  int    $grade - the grade of the students
     * @param  int    $class - the class of the students
     * @param  String $type - the type of the attendance records to query
     * @return an array contains attendance records
     */
    public function get_attendance_records_by_class($school_year, $time, $grade, $class, $type)
    {
        $before_time = $this->get_before_time($time);
        return $this->__CI->Attendance_model->get_attendance_records_by_class($school_year, $before_time, 
                                                                              $grade, $class, $type);
    }

    /**
     * Get attendance records for administrators monitors in a certain grade.
     * @param  int    $school_year - the school year to query
     * @param  int    $grade - the grade of the students
     * @param  String $time - the value is one of ( 'a-week', 'two-weeks'
     *         'a-month', 'all' ), stands for which record to query
     * @return an array contains attendance records
     */
    public function get_attendance_records_by_grade($year, $grade, $time)
    {
        $before_time = $this->get_before_time($time);
        $attendance_records = $this->__CI->Attendance_model->get_attendance_records_by_grade($year, $grade, $before_time);
        return $attendance_records;
    }

    /**
     * Get the start time to query.
     * @param  String $time - the value is one of ( 'a-week', 'two-weeks'
     *         'a-month', 'all' ), stands for which record to query
     * @return an timestamp which stand for the start time to query
     */
    private function get_before_time($time)
    {
        $before_time = 0;
        switch ($time) {
            case 'a-week':
                $before_time = mktime(0, 0, 0, date("m"), date("d") - 7, date("Y"));
                break;
            case 'two-weeks':
                $before_time = mktime(0, 0, 0, date("m"), date("d") - 14, date("Y"));
                break;
            case 'a-month':
                $before_time = mktime(0, 0, 0, date("m") - 1, date("d"), date("Y"));
                break;
            case 'all':
            default:
                $before_time = mktime(0, 0, 0, 0, 0, 0);
                break;
        }

        return date('Y-m-d', $before_time);
    }

    /**
     * Get the students list in a certain grade.
     * @param  int $grade - the name of the grade
     * @return an array which contains students list if the query is
     *         successful, or false if the query is false
     */
    public function get_students_list_by_grade($grade)
    {
        return $this->__CI->Students_model->get_students_list_by_grade($grade);
    }

    /**
     * Get the students list in a certain class.
     * @param  int $grade - the name of the grade
     * @param  int $class - the name of the class
     * @return an array which contains students list if the query is
     *         successful, or false if the query is false
     */
    public function get_students_list_by_class($grade, $class)
    {
        return $this->__CI->Students_model->get_students_list_by_class($grade, $class);
    }

    /**
     * Get attendance list for different user group.
     * @param  String $user_group - the user group of the user
     * @return an array contains attendance rules
     */
    public function get_rules_list($user_group)
    {
        $rules_list = array();
        switch ($user_group) {
            case 'Study-Monitors':
                $rules_list = $this->__CI->Attendance_rules_model->select('Study');
                break;
            case 'Sports-Monitors':
                $rules_list = $this->__CI->Attendance_rules_model->select('Sports');
                break;
            case 'Administrators':
                $rules_list = $this->__CI->Attendance_rules_model->select();
                break;
            default:
                break;
        }

        return $rules_list;
    }

    /**
     * Handle study monitors and sports monitors posting attendance data
     * requests.
     * @return an array which contains the query flags
     */
    public function add_attendance_record($attendance_data)
    {
        $attendance_record = array(
                'school_year'       => $this->get_current_school_year(),
                'semester'          => $this->get_current_semester(),
                'student_id'        => $attendance_data['student_id'],
                'time'              => date('Y-m-d H:i', strtotime($attendance_data['datetime'])),
                'rule_id'           => $this->__CI->Attendance_rules_model->get_rule_id($attendance_data['reason'])
            );
        $result = array(
                'is_successful'     => $this->__CI->Attendance_model->insert($attendance_record)
            );
        return $result;
    }

    /**
     * Handle administrators editing attendance data requests.
     * @return an array which contains the query flags
     */
    public function edit_attendance_record($attendance_data)
    {
        $attendance_record = array(
                'student_id'        => $attendance_data['student_id'],
                'old_time'          => date('Y-m-d H:i', strtotime($attendance_data['old_time'])),
                'new_time'          => date('Y-m-d H:i', strtotime($attendance_data['new_time'])),
                'rule_id'           => $this->__CI->Attendance_rules_model->get_rule_id($attendance_data['reason'])
            );
        $result = array(
                'is_successful'     => $this->__CI->Attendance_model->update($attendance_record)
            );
        return $result;
    }

    /**
     * Handle administrators deleting attendance data requests.
     * @param  String    $student_id - the student id of the student
     * @param  TimeStamp $time - the time when the event happened
     * @return an array which contains the query flags
     */
    public function delete_attendance_record($student_id, $time)
    {
        $result = array(
                'is_successful'     => $this->__CI->Attendance_model->delete_record($student_id, $time)
            );
        return $result;
    }

    public function get_rooms_list($grade)
    {
        return $this->__CI->Students_model->get_rooms_list($grade);
    }
}

/* End of file lib_routine.php */
/* Location: ./application/libraries/lib_routine.php */