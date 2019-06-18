<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * The class handles all requests about routine.
 * @author: Haozhe Xie <cshzxie@gmail.com>
 */
class Lib_scores {
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
        $this->__CI->load->model('Students_model');
        $this->__CI->load->model('Scores_model');
        $this->__CI->load->model('Courses_model');
        $this->__CI->load->model('Education_plans_model');

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
     * Get available years to select from existing data.
     *
     * The function is mainly used for students to query their score records 
     * from the database. So the available years should not earlier than when 
     * they attend university.
     *
     * @param String  student_id - the student id of the student
     * @return an array contains all available years
     */
    public function get_available_years($student_id)
    {
        $available_years = $this->__CI->Scores_model->get_available_years($student_id);
        return $available_years;
    }

    /**
     * Get available years to select from existing data.
     *
     * The function is mainly used for administrators to get all available
     * years for score records in the database.
     * 
     * @return an array contains all available years
     */
    public function get_all_available_years()
    {
        $available_years = $this->__CI->Scores_model->get_all_available_years();
        return $available_years;
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

    public function get_all_courses() 
    {
        $courses = $this->__CI->Courses_model->get_all_courses();
        return $courses;
    }

    public function get_education_plan($school_year, $grade)
    {
        $available_courses = $this->__CI->Education_plans_model->get_education_plan($school_year, $grade);
        return $available_courses;
    }

    public function add_education_plan($school_year, $grade, $course_id)
    {
        $education_plan = array(
            'school_year'   => $school_year,
            'grade'         => $grade,
            'course_id'     => $course_id,
        );
        $this->__CI->Education_plans_model->insert($education_plan);
        return true;
    }

    public function delete_education_plan($school_year, $grade, $course_id)
    {
        $education_plan = array(
            'school_year'   => $school_year,
            'grade'         => $grade,
            'course_id'     => $course_id,
        );
        $this->__CI->Education_plans_model->delete($education_plan);
        return true;
    }

    public function get_transcripts_records_by_student($school_year, $semester, $student_id)
    {
        $transcripts_records = $this->__CI->Scores_model->get_transcripts_records_by_student($school_year, $semester, $student_id);
        if ( $transcripts_records ) {
            foreach ( $transcripts_records as &$record ) {
                $record['grade_point'] = $this->get_grade_point($record['final_score'], $record['is_hierarchy'], $record['is_passed']);
                if ( $record['final_score'] >= 60 ) {
                    $record['is_passed'] = '';
                } else {
                    if ( $record['is_passed'] ) {
                        $record['is_passed'] = '及格';
                    } else {
                        $record['is_passed'] = '不及格';
                    }
                }
                if ( $record['paper_score'] == null ) {
                    $record['paper_score'] = '';
                }
                if ( $record['is_hierarchy'] ) {
                    $record['final_score'] = $this->get_rank($record['final_score']);
                }
            }
        }
        return $transcripts_records;
    }

    public function get_transcripts_records_by_grade($school_year, $grade, $course_id)
    {
        $transcripts_records = null;
        if ( $course_id === 'all' ) {
            $transcripts_records = $this->get_transcripts_ranking_by_grade($school_year, $grade);
        } else {
            $transcripts_records = $this->__CI->Scores_model->get_transcripts_records_by_grade_and_course_id($grade, $course_id);            
        }
        
        if ( $transcripts_records ) {
            foreach ( $transcripts_records as &$record ) {
                if ( $record['is_hierarchy'] ) {
                    $record['final_score'] = $this->get_rank($record['final_score']);
                }
            }
        }
        return $transcripts_records;
    }

    public function get_transcripts_ranking_by_grade($school_year, $grade)
    {
        return $this->get_transcripts_ranking($school_year, $grade);
    }

    public function get_transcripts_ranking($school_year, $grade)
    {
        $students = $this->get_map($this->__CI->Students_model->get_students_list_by_grade($grade), 'student_id');
        $courses  = $this->get_map($this->__CI->Education_plans_model->get_education_plan($school_year, $grade), 'course_id');
        $scores   = $this->__CI->Scores_model->get_transcripts_records_by_grade_and_school_year($school_year, $grade);

        if ( $scores == null ) {
            return null;
        }
        foreach ( $scores as $score ) {
            $student_id = $score['student_id'];
            $course_id  = $score['course_id'];
            $score      = $this->get_score($score['final_score']);
            if ( array_key_exists($course_id, $courses) ) {
                if ( !array_key_exists('total_score', $students[$student_id]) ) {
                    $students[$student_id]['total_score'] = 0;
                    $students[$student_id]['total_credits'] = 0;
                }
                $credits = $courses[$course_id]['credits'];
                $students[$student_id]['total_score'] += $score * $credits;
                $students[$student_id]['total_credits'] += $credits;
            }
        }

        foreach ( $students as &$student ) {
            $student['is_hierarchy'] = false;
            if ( !array_key_exists('total_credits', $student) || $student['total_credits'] == 0 ) {
                $student['final_score'] = 0;
                continue;
            }
            $student['final_score']  = round($student['total_score'] / $student['total_credits'], 4);
        }
        foreach ( $students as $key => $row ) {
            $sorting[$key] = $row['final_score'];
        }
        array_multisort($sorting, SORT_DESC, $students);
        $ranking = 0;
        foreach ( $students as &$student ) {
            $student['ranking'] = ++ $ranking;
        }
        
        return $students;
    }

    public function get_gpa_by_student($student_id)
    {
        $student = $this->__CI->Students_model->select($student_id);
        $grade   = $student['grade'];
        $gpa     = $this->get_gpa($grade);

        if ( $gpa != null ) {
            foreach ( $gpa as $record ) {
                if ( $record['student_id'] === $student_id ) {
                    return $record;
                }
            }
        }
        return null;
    }

    public function get_gpa_by_grade($grade)
    {
        return $this->get_gpa($grade);
    }

    private function get_gpa($grade)
    {
        $students = $this->get_map($this->__CI->Students_model->get_students_profile_list_by_grade($grade), 'student_id');
        $courses  = $this->get_map($this->__CI->Education_plans_model->get_all_education_plans(), 'course_id');
        $scores   = $this->__CI->Scores_model->get_transcripts_records_by_grade($grade);

        if ( $scores == null ) {
            return null;
        }
        foreach ( $scores as $score ) {
            $student_id     = $score['student_id'];
            $course_id      = $score['course_id'];
            $grade_point    = $this->get_grade_point($score['final_score'], $score['is_hierarchy'], $score['is_passed']);
            if ( array_key_exists($course_id, $courses) ) {
                if ( !array_key_exists('courses', $students[$student_id]) ) {
                    $students[$student_id]['courses'] = array();
                }
                if ( array_key_exists($course_id, $students[$student_id]['courses']) ) {
                    $current_grade_point = $students[$student_id]['courses'][$course_id];
                    if ( $grade_point <= $current_grade_point ) {
                        continue;
                    }
                }
                $students[$student_id]['courses'][$course_id] = $grade_point;
            }
        }
        foreach ( $students as &$student ) {
            $student['total_grade_points'] = 0;
            $student['total_credits'] = 0;
            foreach ( $student['courses'] as $course_id => $grade_point ) {
                $credits = $courses[$course_id]['credits'];
                $student['total_grade_points'] += $grade_point * $credits;
                $student['total_credits'] += $credits;
            }
            if ( $student['total_credits'] == 0 ) {
                $student['gpa'] = 0;
                continue;
            }
            $student['gpa'] = round($student['total_grade_points'] / $student['total_credits'], 4);
        }
        foreach ( $students as $key => $row ) {
            $sorting[$key] = $row['gpa'];
        }
        array_multisort($sorting, SORT_DESC, $students);
        $ranking = 0;
        foreach ( $students as &$student ) {
            $student['ranking'] = ++ $ranking;
        }
        return $students;
    }

    private function get_map(&$array, $key_name)
    {
        $map = array();

        if ( $array == null ) {
            return;
        }
        foreach ( $array as $item ) {
            $key       = $item[$key_name];
            $map[$key] = $item;
        }
        return $map;
    }

    private function get_array($map)
    {
        $array = array();

        if ( $map == null ) {
            return;
        }
        foreach ( $map as $item ) {
            array_push($array, $item);
        }
        return $array;
    }

    /**
     * Import scores from an excel file.
     * @param  String $file_path - the path of the excel file
     * @param  Array  $result - an array contains query flags.
     * @return true if query is successful
     */
    public function import_scores($file_path, &$result)
    {
        $this->__CI->load->library('lib_excel');
        $data = $this->__CI->lib_excel->get_data_from_excel($file_path);

        $number_of_records = count($data);
        $result['is_query_successful'] = true;
        for ( $i = 1; $i < $number_of_records; ++ $i ) {
            $score = $this->get_scores_array($data[$i]);
            if ( $score['course_type'] != 1 ) {
                continue;
            }
            $query_result = $this->import_score_record($score);
            $result['is_query_successful']  &= $query_result['is_successful'];
            if ( $query_result['is_successful'] ) {
                $result['success_message']  .= $score['student_name'].'的'.$score['course_name'].'成绩信息已成功导入.<br />';
            } else {
                $result['success_message']  .= $score['student_name'].'的'.$score['course_name'].'成绩信息未能成功导入.<br />';
            }
        }
        return $result['is_query_successful'];
    }

    /**
     * [import_score_record description]
     * @param  [type] $score_record [description]
     * @return [type]               [description]
     */
    private function import_score_record(&$score_record)
    {
        $result = array(
                'is_successful' => false
            );
        $score  = array(
                'school_year'   => $score_record['school_year'],
                'semester'      => $score_record['semester'],
                'student_id'    => $score_record['student_id'],
                'course_id'     => $score_record['course_id'],
                'paper_score'   => $score_record['paper_score'],
                'final_score'   => $score_record['final_score'], 
                'is_hierarchy'  => $score_record['is_hierarchy'], 
                'is_passed'     => $score_record['is_passed'] 
            );
        $result['is_successful'] = $this->__CI->Scores_model->insert($score);

        return $result;
    }

    /**
     * Get information of the user which read from an excel file to 
     * a row array.
     * @param  Array $record - an array contains user's information
     * @return an array which contains the information of the user
     */
    private function get_scores_array(&$record)
    {
        $final_score            =  $this->get_score($record[15]);
        $score = array(
                'school_year'   => (int)( ((int)$record[0] - 1) / 2 + 2002),
                'semester'      => ((int)$record[0] % 2 != 0 ? 1 : 2),
                'student_id'    => (string)$record[1],
                'student_name'  => (string)$record[2],
                'course_id'     => (string)$record[4],
                'course_name'   => (string)$record[5],
                'course_type'   => (int)$record[7],
                'paper_score'   => $this->get_score($record[13]),
                'final_score'   => $final_score,
                'is_hierarchy'  => $this->start_with((string)$record[15], 'Z'),
                'is_passed'     => ( $final_score >= 60 ? true : ( (string)$record[17] == 'Y11' ? true :  false) )
            );
        return $score;
    }

    private function start_with($str, $needle) {
        return strpos($str, $needle) === 0;
    }

    /**
     * Convert score from hundred mark system into hierarchy.
     * @param  [type] $score [description]
     * @return [type]        [description]
     */
    private function get_score($score)
    {
        if ( is_numeric($score) ) {
            if ( $score > 100 ) {
                return 100;
            } else {
                return $score;
            }
        } else {
            if ( $score == 'Z11' ) {
                return 95;
            } else if ( $score == 'Z12' ) {
                return 85;
            } else if ( $score == 'Z13' ) {
                return 75;
            } else if ( $score == 'Z14' ) {
                return 65;
            } else {
                return 0;
            }
        }
    }

    private function get_rank($score)
    {
        if ( $score == 95 ) {
            return '优';
        } else if ( $score == 85 ) {
            return '良';
        } else if ( $score == 75 ) {
            return '中';
        } else if ( $score == 65 ) {
            return '及格';
        } else if ( $score == 0 ) {
            return '不及格';
        } else {
            return $score;
        }
    }

    /**
     * Get the grade point of a certain grade.
     * @param  int     $score        [description]
     * @param  boolean $is_hierarchy [description]
     * @return the grade point of the grade
     */
    private function get_grade_point($score, $is_hierarchy, $is_passed)
    {
        if ( $is_hierarchy ) {
            if ( $score == 95 ) {
                return 3.9;
            } else if ( $score == 85 ) {
                return 3.0;
            } else if ( $score == 70 ) {
                return 2.0;
            } else if ( $score == 60 ) {
                return 1.2;
            } else {
                if ( $is_passed ) {
                    return 1;
                } else {
                    return 0;
                }
            }
        } else {
            if ( $score >= 95 ) {
                return 4.3;
            } else if ( $score >= 90 && $score < 95 ) {
                return 4;
            } else if ( $score >= 85 && $score < 90 ) {
                return 3.7;
            } else if ( $score >= 82 && $score < 85 ) {
                return 3.3;
            } else if ( $score >= 78 && $score < 82 ) {
                return 3;
            } else if ( $score >= 75 && $score < 78 ) {
                return 2.7;
            } else if ( $score >= 72 && $score < 75 ) {
                return 2.3;
            } else if ( $score >= 68 && $score < 72 ) {
                return 2;
            } else if ( $score >= 66 && $score < 68 ) {
                return 1.7;
            } else if ( $score >= 64 && $score < 66 ) {
                return 1.3;
            } else if ( $score >= 60 && $score < 64 ) {
                return 1;
            } else {
                if ( $is_passed ) {
                    return 1;
                } else {
                    return 0;
                }
            }
        }
    }
}

/* End of file lib_scores.php */
/* Location: ./application/libraries/lib_scores.php */
