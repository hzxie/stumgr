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
    }

    /**
     * [is_participated description]
     * @param  int     $year - the year when the peer assessment carried on
     * @param  String  $student_id - the student id of the student
     * @return true if the student has participated in the peer assessment
     */
    public function is_participated($year, $student_id)
    {
        $assessment = $this->__CI->Assessment_model->select($year, $student_id);
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

        $year = date('Y');
        foreach ( $students as $student ) {
            $student_id = $student['student_id'];
            $current_votes = $this->__CI->Assessment_model->select($year, $student_id);
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