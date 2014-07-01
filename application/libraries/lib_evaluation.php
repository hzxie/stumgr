<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * The class handles all requests about evaluation.
 * @author: Xie Haozhe <zjhzxhz@gmail.com>
 */
class Lib_evaluation {
    /**
     * @var A instance of CodeIgniter.
     */
    private $__CI;
    
    /**
     * The contructor of the Evaluation Library class.
     */
    public function __construct() 
    {
        $this->__CI =& get_instance();
        $this->__CI->load->model('Students_model');
        $this->__CI->load->model('Assessment_model');
        $this->__CI->load->model('Options_model');
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
    public function get_available_years_for_assessment()
    {
        $available_years        = $this->__CI->Assessment_model->get_available_years();
        $current_size           = count($available_years);
        $current_school_year    = $this->get_current_school_year();

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

    public function get_assessment_records($school_year, $grade)
    {
        return $this->__CI->Assessment_model->get_assessment_records($school_year, $grade);
    }

    /**
     * 打开/关闭学生互评系统.
     *
     * 若是本年度第一次使用该系统, 系统会初始化. 即在数据库中为每一个学生用户创建一
     * 条空记录.
     * 
     * @return 系统状态是否成功切换
     */
    public function switch_is_peer_assessment_active($is_peer_assessment_active)
    {
        if ( $is_peer_assessment_active ) {
            $students_list  = $this->__CI->Students_model->get_all_students_list();
            foreach ( $students_list as $student ) {
                $record     = array(
                    'school_year'   => $this->get_current_school_year(),
                    'student_id'    => $student['student_id'],
                );

                if ( !$this->is_record_exists($record) ) {
                    $this->__CI->Assessment_model->insert($record);
                }
            }
        }
        $this->update_is_peer_assessment_active($is_peer_assessment_active);

        return true;
    }

    private function is_record_exists($record)
    {
        $school_year = $record['school_year'];
        $student_id  = $record['student_id'];
        return $this->__CI->Assessment_model->select($school_year, $student_id);
    }

    private function update_is_peer_assessment_active($is_peer_assessment_active)
    {
        return $this->__CI->Options_model->update( 'is_peer_assessment_active', $is_peer_assessment_active );
    }

    /**
     * [is_participated description]
     * @param  int     $school_year - the school year when the peer assessment carried on
     * @param  String  $student_id - the student id of the student
     * @return true if the student has participated in the peer assessment
     */
    public function is_participated($school_year, $student_id)
    {
        $assessment = $this->__CI->Assessment_model->select($school_year, $student_id);
        return $assessment['is_participated'];
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
     * Handle user's post peer assessment votes requests.
     * @param  Array  $posted_votes     [description]
     * @param  Array  $students         [description]
     * @param  String $voter_student_id [description]
     * @param  Array  $options          [description]
     * @return an array which contains the query flags
     */
    public function post_votes(&$posted_votes, &$students, $voter_student_id, &$options)
    {
        if ( !$this->is_all_proportion_legal($posted_votes, $options, $students, $voter_student_id) ) {
            return false;
        }

        $school_year = $this->get_current_school_year();
        foreach ( $students as $student ) {
            $student_id     = $student['student_id'];
            $current_votes  = $this->__CI->Assessment_model->select($school_year, $student_id);
            if ( $student_id == $voter_student_id ) {
                $current_votes['is_participated'] = true;
            } else {
                ++ $current_votes['moral_'.$posted_votes[$student_id]['moral']];
                ++ $current_votes['strength_'.$posted_votes[$student_id]['strength']];
                ++ $current_votes['ability_'.$posted_votes[$student_id]['ability']];
            }
            $this->__CI->Assessment_model->update($current_votes);
        }
        return true;
    }

    /**
     * [is_all_proportion_legal description]
     * @param  [type]  $posted_votes [description]
     * @param  [type]  $options      [description]
     * @return boolean               [description]
     */
    private function is_all_proportion_legal(&$posted_votes, &$options, &$students, $voter_student_id)
    {
        $votes = $this->get_votes_statistics($posted_votes, $students, $voter_student_id);
        $number_of_students = count($students);
        $min_number_of_excellent_students = floor($number_of_students * $options['min_excellent_percents']);
        $max_number_of_excellent_students = floor($number_of_students * $options['max_excellent_percents']);
        $min_number_of_good_students      = floor($number_of_students * $options['min_good_percents']);
        $max_number_of_good_students      = floor($number_of_students * $options['max_good_percents']);
        $min_number_of_medium_students    = floor($number_of_students * $options['min_medium_percents']);
        $max_number_of_medium_students    = floor($number_of_students * $options['max_medium_percents']);

        if ( !($votes['moral_excellent']    >= $min_number_of_excellent_students && $votes['moral_excellent']    <= $max_number_of_excellent_students) ||
             !($votes['strength_excellent'] >= $min_number_of_excellent_students && $votes['strength_excellent'] <= $max_number_of_excellent_students) ||
             !($votes['ability_excellent']  >= $min_number_of_excellent_students && $votes['ability_excellent']  <= $max_number_of_excellent_students) ) {
            return false;
        }
        if ( !($votes['moral_good']    >= $min_number_of_good_students && $votes['moral_good']    <= $max_number_of_good_students) ||
             !($votes['strength_good'] >= $min_number_of_good_students && $votes['strength_good'] <= $max_number_of_good_students) ||
             !($votes['ability_good']  >= $min_number_of_good_students && $votes['ability_good']  <= $max_number_of_good_students) ) {
            return false;
        }
        if ( !(($votes['moral_medium']    + $votes['moral_poor'])    >= $min_number_of_medium_students && ($votes['moral_medium']    + $votes['moral_poor'])    <= $max_number_of_medium_students) ||
             !(($votes['strength_medium'] + $votes['strength_poor']) >= $min_number_of_medium_students && ($votes['strength_medium'] + $votes['strength_poor']) <= $max_number_of_medium_students) ||
             !(($votes['ability_medium']  + $votes['ability_poor'])  >= $min_number_of_medium_students && ($votes['ability_medium']  + $votes['ability_poor'])  <= $max_number_of_medium_students) ) {
            return false;
        }

        return true;
    }

    /**
     * [get_votes_statistics description]
     * @param  [type] $posted_votes [description]
     * @return [type]               [description]
     */
    private function get_votes_statistics(&$posted_votes, &$students, $voter_student_id)
    {
        $votes = array(
            'moral_excellent'       => 0, 'moral_good'      => 0, 'moral_medium'    => 0, 'moral_poor'      => 0,
            'strength_excellent'    => 0, 'strength_good'   => 0, 'strength_medium' => 0, 'strength_poor'   => 0,
            'ability_excellent'     => 0, 'ability_good'    => 0, 'ability_medium'  => 0, 'ability_poor'    => 0
        );
        foreach ( $students as $student ) {
            $student_id = $student['student_id'];
            if ( $student_id == $voter_student_id ) {
                continue;
            }
            ++ $votes['moral_'.$posted_votes[$student_id]['moral']];
            ++ $votes['strength_'.$posted_votes[$student_id]['strength']];
            ++ $votes['ability_'.$posted_votes[$student_id]['ability']];
        }

        return $votes;
    }
}

/* End of file lib_evaluation.php */
/* Location: ./application/libraries/lib_evaluation.php */