<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 系统的核心业务类. 管理员用户的Controller.
 * 
 * @author Xie Haozhe <zjhzxhz@gmail.com>
 */
class Admin extends CI_Controller {
    /**
     * @var 一个包含登录用户信息的数组.
     */
    private $profile;

    /**
     * @var 一个包含系统参数的数组.
     */
    private $options;

    /**
     * 构造函数. 加载Business层的Library.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->library('lib_accounts');
        $this->load->library('lib_routine');
        $this->load->library('lib_scores');
        $this->load->library('lib_evaluation');
        $this->load->library('lib_utils');

        $session = $this->session->all_userdata();
        if ( !($session['is_logged_in'] && $session['is_administrator']) ) {
            redirect(base_url(). 'accounts');
        }

        $this->get_profile($session['username']);
        $this->get_options();
    }

    /**
     * 获取用户的用户信息.
     * @param  String  $username - 用户名
     */
    private function get_profile($username)
    {
        $this->profile = array(
            'username'          => $username,
            'display_name'      => $username,
            'is_administrator'  => true
        );
    }

    /**
     * 获取系统参数.
     */
    private function get_options()
    {
        $this->load->model('Options_model');
        $options = $this->Options_model->select();
        foreach ( $options as $option ) {
            $this->options[$option['option_name']] = $option['option_value'];
        }
    }

    /**
     * 获取某一个系统参数的值.
     * @param  String $option_name - 系统参数的名称
     * @return 系统参数的值
     */
    private function get_option($option_name)
    {
         return $this->options[$option_name];
    }
    
    /**
     * Load the index page for the admin class.
     *
     * The index.php is a frame and doesn't contain any
     * information.
     */
    public function index()
    {
        $navigator_item = array(
            '欢迎'            => base_url().'admin#welcome',
            '账户'            => base_url().'admin#profile'
        );
        $data = array( 
            'profile'           => $this->profile, 
            'navigator_item'    => $navigator_item 
        );
        $this->load->view('/admin/index.php', $data);
    }

    /**
     * The function will be invoked by an ajax request from 
     * index.php.
     *
     * The function will invoke another function to get the data 
     * which is needed by the page.
     * 
     * @param  String $page - the name of the page to load
     */
    public function load($page = '')
    {
        $function = 'get_data_for_'.$page;
        if ( method_exists($this, $function) ) {
            $data = $this->$function();
            $this->load->view("/admin/$page.php", $data);
        } else {
            return false;
        }
    }

    /**
     * Get data for the welcome.php page.
     * @return an array which 'contains data which the page needs
     */
    public function get_data_for_welcome()
    {
        $session = $this->session->all_userdata();
        $welcome = array(
            'display_name'          => $this->profile['display_name'],
            'ip_address'            => $session['ip_address'],
            'last_time_signin'      => $session['last_time_signin'],
            'allow_auto_sign_in'    => $session['allow_auto_sign_in']
        );
        $data = array( 'welcome' => $welcome, 'profile' => $this->profile );
        return $data;
    }

    /**
     * Get data for the profile.php page.
     * @return an array which 'contains data which the page needs
     */
    public function get_data_for_profile()
    {
        $account = $this->lib_accounts->get_last_time_change_password($this->profile['username']);
        $data = array( 'account' => $account, 'profile' => $this->profile );
        return $data;
    }

    /**
     * Handle users' changing password requests.
     * @return an array which contains the query flags
     */
    public function change_password()
    {
        $old_password       = $this->input->post('old_password');
        $new_password       = $this->input->post('new_password');
        $confirm_password   = $this->input->post('password_again');

        $result = $this->lib_accounts->change_password($this->profile['username'], $old_password,
                                                       $new_password, $confirm_password);
        echo json_encode($result);
    }

    /**
     * Get data for addusers.php page.
     *
     * IMPORTANT: No data is needed by the page. However, each page
     *            will invoke a function when the page is loaded.
     *            So, you CANNOT remove this function.
     */
    public function get_data_for_add_users() { }

    /**
     * Add a user from the form.
     * @return an array which contains the query flags
     */
    public function add_user() 
    {
        $user_information = array(
            'student_id'    => $this->input->post('student_id'),
            'student_name'  => $this->input->post('student_name'),
            'grade'         => $this->input->post('grade'),
            'class'         => $this->input->post('class'),
            'room'          => $this->input->post('room'),
            'password'      => $this->input->post('password')
        );

        $result = $this->lib_accounts->add_user($user_information);
        echo json_encode($result);
    }

    /**
     * Handle administrator's uploading excel files requests.
     * The function is mainly used for importing data to the database.
     * 
     * @return an array with a boolean flag which infers if the operation
     *         is successful, and an extra message.
     */
    private function upload_files()
    {
        $config['upload_path']      = './application/uploads/';
        $config['allowed_types']    = 'xls|xlsx';
        $config['max_size']         = '1024';
        $this->load->library('lib_upload', $config);

        return $this->lib_upload->do_upload();
    }

    /**
     * Get data for logs.php page.
     *
     * IMPORTANT: No data is needed by the page. However, each page
     *            will invoke a function when the page is loaded.
     *            So, you CANNOT remove this function.
     */
    public function get_data_for_logs() { }

    /**
     * Add users from an excel file.
     * @return an array which contains the query flags
     */
    public function add_users() 
    {
        $result = array(
                'is_successful'         => false,
                'is_upload_successful'  => false,   'is_query_successful'   => false,
                'success_message'       => '',      'error_message'         => ''
        );

        $upload_result = $this->upload_files();
        $result['is_upload_successful']     = $upload_result['is_successful'];
        if ( !$result['is_upload_successful'] ) {
            $result['error_message']        = $upload_result['extra_message'];
        } else {
            $result['is_query_successful']  = $this->lib_accounts->add_users($upload_result['extra_message'], $result);
            $result['is_successful']        = $result['is_query_successful'];
            $this->log_messages($result['error_message'], $result['success_message']);
        }

        echo json_encode($result);
    }

    /**
     * Log error messages and success messages to local temporary file.
     * @param  String $error_message   - a string contains error message
     * @param  String $success_message - a string contains success message
     */
    private function log_messages(&$error_message, &$success_message)
    {
        if ( !empty($error_message) ) {
            $error_log_file_path = APPPATH.'logs/error.log';
            $this->log_to_file($error_log_file_path, $error_message);
        }
        if ( !empty($success_message) ) {
            $success_log_file_path = APPPATH.'logs/success.log';
            $this->log_to_file($success_log_file_path, $success_message);   
        }
    }

    /**
     * Log error messages and success messages to local temporary file.
     * @param  String $target_name - target file path on the server
     * @param  String $content - message to log to file
     */
    private function log_to_file($target_name, &$content)
    {
        $file = fopen($target_name,"w");
        fwrite($file, $content);
        fclose($file);
    }

    /**
     * Get data for editusers.php page.
     * @return an array which contains data which the page needs
     */
    public function get_data_for_edit_users() 
    {
        $available_grades = $this->lib_accounts->get_available_grades();
        $user_groups = $this->lib_accounts->get_user_groups_list();
        $data = array( 'available_grades' => $available_grades, 'user_groups' => $user_groups );
        return $data;
    }

    /**
     * Get students' profile list in a certain grade.
     * @param  int $grade - the grade of the students
     * @return an array which contains students' profile list if the 
     *         query success
     */
    public function get_students_profile_list($grade)
    {
        $available_grades = $this->lib_accounts->get_available_grades();
        $students = array();
        if ( $this->lib_utils->in_array($available_grades, 'grade', $grade) ) {
            $students = $this->lib_accounts->get_students_profile_list($grade);
        }
        $result = array(
                'is_successful' => ( count($students) != 0 ),
                'students'      => $students
        );
        echo json_encode($result);
    }

    /**
     * Handle getting user's profile requests.
     * @param  String $student_id - the student id of the student
     * @return the profile of the student
     */
    public function get_user_profile($student_id)
    {
        $data = $this->lib_accounts->get_profile($student_id);
        echo json_encode($data);
    }

    /**
     * Handle edting user's profile requests.
     * @param  String $student_id - the student id of the student
     * @return an array which contains the query flags
     */
    public function edit_user_profile($student_id)
    {
        $profile = array(
            'student_name'          => $this->input->post('student_name'),
            'grade'                 => $this->input->post('grade'),
            'class'                 => $this->input->post('class'),
            'user_group_name'       => $this->input->post('user_group_name'),
            'room'                  => $this->input->post('room'),
            'mobile'                => $this->input->post('mobile'),
            'email'                 => $this->input->post('email'),
            'password'              => $this->input->post('password')
        );
        $result = $this->lib_accounts->edit_user_profile($student_id, $profile, $result);

        echo json_encode($result);
    }

    /**
     * Handle Deleting a user's account requests.
     * @param  String $student_id - the student id of the student
     * @return an array which contains the query flags
     */
    public function delete_account($student_id)
    {
        $result = array( 'is_successful' => false );
        $result['is_successful'] = $this->lib_accounts->delete_account($student_id);

        echo json_encode($result);
    }

    /**
     * Handle deleting users' account in a certain grade.
     * @param  int $grade - the grade of the students
     * @return an array which contains the query flags
     */
    public function delete_accounts($grade)
    {
        $result = array( 'is_successful' => false );
        $result['is_successful'] = $this->lib_accounts->delete_accounts($grade);

        echo json_encode($result);
    }

    /**
     * Get data for the rules.php page.
     * @return an array which contains data which the page needs
     */
    public function get_data_for_rules()
    {

    }

    /**
     * Get data for the attendance.php page.
     * @return an array which contains data which the page needs
     */
    public function get_data_for_attendance()
    {
        $extra = array(
            'current_school_year'   => $this->lib_routine->get_current_school_year(),
            'current_semester'      => $this->lib_routine->get_current_semester(),
        );
        $data = array( 
            'available_years'   => $this->lib_routine->get_all_available_years_for_attendance(),
            'available_grades'  => $this->lib_routine->get_available_grades(),
            'rules'             => $this->lib_routine->get_rules_list('Administrators'),
            'extra'             => $extra
        );
        return $data;
    }

    /**
     * Handle administrators' getting attendance records requests.
     * @param  int    $school_year - the year to query
     * @param  int    $grade - the grade of the students
     * @param  String $time  - the value is one of ( 'a-week', 'two-weeks'
     *         'a-month', 'all' ), stands for which record to query
     * @return an array contains attendance records with query flags
     */
    public function get_attendance_records($school_year, $grade, $time)
    {
        $attendance_records = $this->lib_routine->get_attendance_records_by_grade($school_year, $grade, $time);
        $result = array(
            'is_successful' => ($attendance_records != false),
            'records'       => $attendance_records
        );
        echo json_encode($result);
    }

    /**
     * Handle administrators' editing attendance records requests.
     * @return an array which contains a query flag
     */
    public function edit_attendance_records()
    {
        $attendance_data = array(
            'student_id'    => $this->input->post('student_id'),
            'old_time'      => $this->input->post('old_time'),
            'new_time'      => $this->input->post('new_time'),
            'reason'        => $this->input->post('reason')
        );
        $result = array(
            'is_successful' => $this->lib_routine->edit_attendance_record($attendance_data)
        );
        echo json_encode($result);
    }

    /**
     * Handle administrators' deleting attendance records requests.
     * @return an array which contains a query flag
     */
    public function delete_attendance_records()
    {
        $student_id = $this->input->post('student_id');
        $time       = $this->input->post('time');
        $result = array(
            'is_successful' => $this->lib_routine->delete_attendance_record($student_id, $time)
        );
        echo json_encode($result);
    }

    /**
     * Get data for the hygiene.php page.
     * @return an array which contains data which the page needs
     */
    public function get_data_for_hygiene()
    {

    }

    /**
     * Get data for the scoresettings.php page.
     * @return an array which contains data which the page needs
     */
    public function get_data_for_score_settings()
    {
        
    }

    /**
     * Handle administrators' import scores requests.
     * @return an array which contains the query flags
     */
    public function import_scores()
    {
        $result = array(
                'is_successful'         => false,
                'is_upload_successful'  => false,   
                'is_query_successful'   => false,
                'success_message'       => '',      
                'error_message'         => '',
        );

        $upload_result = $this->upload_files();
        $result['is_upload_successful']     = $upload_result['is_successful'];
        if ( !$result['is_upload_successful'] ) {
            $result['error_message']        = $upload_result['extra_message'];
        } else {
            $result['is_query_successful']  = $this->lib_scores->import_scores($upload_result['extra_message'], $result);
            $result['is_successful']        = $result['is_query_successful'];
            $this->log_messages($result['error_message'], $result['success_message']);
        }

        echo json_encode($result);
    }

    /**
     * Get data for the transcripts.php page.
     * @return an array which contains data which the page needs
     */
    public function get_data_for_transcripts()
    {
        
    }

    /**
     * Get data for the gpa.php page.
     * @return an array which contains data which the page needs
     */
    public function get_data_for_gpa()
    {
        
    }

    /**
     * Get data for the evaluationsettings.php page.
     * @return an array which contains data which the page needs
     */
    public function get_data_for_evaluation_settings()
    {
        $data = array(
            'options'   => $this->options
        );
        return $data;
    }

    /**
     * 打开/关闭学生互评系统.
     *
     * 若是本年度第一次使用该系统, 系统会初始化. 即在数据库中为每一个学生用户创建一
     * 条空记录.
     * 
     * @return 一个包含若干标志位的数组
     */
    public function switch_is_peer_assessment_active()
    {
        $is_peer_assessment_active  = $this->input->post('is_peer_assessment_active');
        $result = array(
            'is_successful' => $this->lib_evaluation->switch_is_peer_assessment_active($is_peer_assessment_active),
        );
        echo json_encode($result);
    }

    /**
     * Get data for the assessment.php page.
     * @return an array which contains data which the page needs
     */
    public function get_data_for_assessment()
    {
        $extra = array(
            'current_school_year'   => $this->lib_evaluation->get_current_school_year(),
            'current_semester'      => $this->lib_evaluation->get_current_semester(),
        );
        $data = array( 
            'available_years'   => $this->lib_evaluation->get_available_years_for_assessment(),
            'available_grades'  => $this->lib_evaluation->get_available_grades(),
            'options'           => $this->options,
            'extra'             => $extra,
        );
        return $data;
    }

    public function get_assessment_records($school_year, $grade)
    {
        $assessment_records = $this->lib_evaluation->get_assessment_records($school_year, $grade);
        $result = array(
            'is_successful' => ($assessment_records != false),
            'records'       => $assessment_records
        );
        echo json_encode($result);
    }

    /**
     * Get data for the rewards.php page.
     * @return an array which contains data which the page needs
     */
    public function get_data_for_rewards()
    {
        
    }

    /**
     * Get data for the result.php page.
     * @return an array which contains data which the page needs
     */
    public function get_data_for_result()
    {
        
    }
}

/* End of file admin.php */
/* Location: ./application/controllers/admin.php */