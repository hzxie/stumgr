<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * The class handles all requests about accounts.
 * @author: Haozhe Xie <cshzxie@gmail.com>
 */
class Lib_accounts {
    /**
     * @var A instance of CodeIgniter.
     */
    private $__CI;
    
    /**
     * The contructor of the Account Library class.
     */
    public function __construct() 
    {
        $this->__CI =& get_instance();
        $this->__CI->load->model('Users_model');
        $this->__CI->load->model('User_groups_model');
        $this->__CI->load->model('Sessions_model');
        $this->__CI->load->model('Students_model');
    }

    /**
     * Handle users' signing in request.
     * @param  String $username the user's username
     * @param  String $password the user's password
     * @return an array which contains the query flags
     */
    public function sign_in($username, $password)
    {
        $passport = array( 'username' => $username, 'password' => $password );
        $result   = array(
                        'is_passed'         => false,   'is_username_empty'     => true,
                        'is_password_empty' => true,    'is_password_correct'   => false,
                        'is_administrator'  => false,   'last_time_signin'      => '0000-00-00 00:00:00'
                    );

        return $this->verify_user($passport, $result);
    }

    /**
     * Verify if the username and password is correct.
     * @param  String $username the user's username
     * @param  String $password the user's password
     * @return an array which contains the query flags
     */
    private function verify_user($passport, &$result)
    {
        $result['is_username_empty'] = empty( $passport['username'] );
        $result['is_password_empty'] = empty( $passport['password'] );
        $result['is_passed'] = (!$result['is_username_empty'] && !$result['is_password_empty']);

        if ( $result['is_passed'] ) {
            $result['is_password_correct'] = $this->is_password_correct($passport, $result);
            $result['is_passed'] &= $result['is_password_correct'];
        }

        return $result;
    }

    /**
     * Verify if the username and password is correct.
     * @param  Array  $passport contains two strings: username and password
     * @param  Array  $result is an array contains query flags.
     * @return true if the username and password is correct
     */
    private function is_password_correct($passport, &$result)
    {
        $username = $passport['username'];
        $password = $passport['password'];

        if ( $query = $this->__CI->Users_model->signin($username, md5($password)) ) {
            $result['is_administrator'] = $query['is_administrator'];
            $result['last_time_signin'] = $query['last_time_signin'];
            return true;
        } else {
            return false;
        }
    }

    /**
     * Handle users' signing in automatically request.
     * @param  Array $session - the session data
     * @return true if allow user sign in automatically
     */
    public function auto_sign_in($session)
    {
        if ( !(isset($session['is_logged_in']) && $session['allow_auto_sign_in']) ) {
            return false;
        }
        if ( !($session['is_logged_in'] && $session['allow_auto_sign_in']) ) {
            return false;
        }
        if ( !$this->__CI->Sessions_model->is_valid($session['session_id']) ) {
            return false;
        }

        return true;
    }

    /**
     * Set userdata in session.
     * @param boolean $is_administrator - if the user is administrator
     * @param boolean $username - the username of the user
     * @param boolean $allow_auto_sign_in - if the user allows auto sign in next time
     * @param String  $last_time_signin - the time when the user signed in
     */
    public function set_user_session($is_administrator, $username, $allow_auto_sign_in, $last_time_signin)
    {
        $session = array(
                        'username'              => $username,
                        'is_logged_in'          => true,
                        'allow_auto_sign_in'    => $allow_auto_sign_in,
                        'is_administrator'      => $is_administrator,
                        'last_time_signin'      => $last_time_signin
                    );
        $this->__CI->session->set_userdata($session);
    }

    /**
     * Get redirect address by user's group.
     * @param  boolean $is_administrator - if the user is administrator
     * @return the address to redirect
     */
    public function get_redirect_url($is_administrator)
    {
        if ( $is_administrator ) {
            return base_url('admin');
        } else {
            return base_url('home');
        }
    }

    /**
     * Return the time when the user change password last time.
     * @param  String $username - the username of the user
     * @return an array which contains the time when the user change 
     *         password the last time
     */
    public function get_last_time_change_password($username)
    {
        $account_info = $this->__CI->Users_model->select($username);
        $return_value = array( 'last_time_change_password' => $account_info['last_time_change_password'] );
        return $return_value;
    }

    /**
     * Get the profile for a certain student.
     * @param  String $student_id - the id of the student
     * @return the information of the student if the query is successful, 
     *         or return false if the query is failed
     */
    public function get_profile($student_id)
    {
        $profile = $this->__CI->Students_model->select($student_id);
        $account = $this->__CI->Users_model->select($student_id);

        if ( $profile && $account ) {
            return array(
                'student_id'                => $profile['student_id'],
                'student_name'              => $profile['student_name'],
                'grade'                     => $profile['grade'],
                'class'                     => $profile['class'],
                'room'                      => $profile['room'],
                'mobile'                    => $profile['mobile'],
                'email'                     => $profile['email'],
                'user_group'                => $this->get_user_group_details($profile['user_groups_id']),
                'last_time_signin'          => $account['last_time_signin'],
                'last_time_change_password' => $account['last_time_change_password']
            );
        } else {
            return false;
        }
    }

    /**
     * Get the list of user groups.
     * @return an array of the list of user groups
     */
    public function get_user_groups_list()
    {
        return $this->__CI->User_groups_model->get_user_groups_list();
    }

    /**
     * Get information of a certain user group.
     * @param  int $user_groups_id - the id of the user group
     * @return the information of the user group
     */
    public function get_user_group_details($user_groups_id)
    {
        return $this->__CI->User_groups_model->select($user_groups_id);
    }

    /**
     * Get available grades to select from existing data.
     * @return an array contains all available grades
     */
    public function get_available_grades()
    {
        return $this->__CI->Students_model->get_available_grades();
    }

    /**
     * Get students' profile in a certain grade.
     * @return an array of students' profile list
     */
    public function get_students_profile_list($grade)
    {
        $students = $this->__CI->Students_model->get_students_profile_list_by_grade($grade);
        foreach ( $students as &$student ) {
            $student['user_group'] = $this->get_user_group_details($student['user_groups_id']);
        }

        return $students;
    }

    /**
     * Handle administrator's adding user requests.
     * @param Array $user_information - the essential information of the 
     *        user
     * @return an array which contains the query flags
     */
    public function add_user($user_information)
    {
        $result = array(
                'is_successful'         => false,
                'is_required_empty'     => true,    'is_information_legal'  => false,
                'is_user_exists'        => true,    'is_query_successful'   => false
            );

        $result['is_successful'] = $this->verify_user_information($user_information, $result);
        if ( $result['is_successful'] ) {
            $result['is_user_exists'] = ( $this->__CI->Users_model->select($user_information['student_id']) ||
                                          $this->__CI->Students_model->select($user_information['student_id']) );
            if ( !$result['is_user_exists'] ) {
                $result['is_query_successful'] = $this->create_user_in_database($user_information);
            }
        }

        $result['is_successful'] &= (!$result['is_user_exists'] && $result['is_query_successful']);
        return $result;
    }

    /**
     * Verify if the information posted is legal.
     * @param  Array $user_information - the essential information of the 
     *         user
     * @param  Array $result - an array which contains the query flags
     * @return true if the information is legal
     */
    private function verify_user_information($user_information, &$result) 
    {
        $result['is_required_empty'] = empty($user_information['student_id']);

        $result['is_information_legal'] = (mb_strlen($user_information['student_name'], 'utf-8') <= 24) &&
                                          (strlen($user_information['password']) <= 16) &&
                                          (strlen($user_information['room']) < 8) &&
                                          preg_match('/^[0-9]{8,10}$/', $user_information['student_id']) &&
                                          preg_match('/^[0-9]{4}$/', $user_information['grade']) &&
                                          preg_match('/^[0-9]{1,2}$/', $user_information['class']);

        $result['is_successful'] = (!$result['is_required_empty'] && $result['is_information_legal']);
        return $result['is_successful'];
    }

    /**
     * Insert the information of the user into database.
     * @param  Array $user_information - the essential information of the 
     *         user
     * @return true if the query is successful
     */
    private function create_user_in_database($user_information)
    {
        $security_information = array(
                'username'      =>  $user_information['student_id'],
                'password'      =>  $user_information['password']
            );
        $basic_information    = array(
                'student_id'    =>  $user_information['student_id'],
                'student_name'  =>  $user_information['student_name'],
                'grade'         =>  $user_information['grade'],
                'class'         =>  $user_information['class'],
                'room'          =>  $user_information['room']
            );
        return ($this->__CI->Users_model->insert($security_information) &&
                $this->__CI->Students_model->insert($basic_information));
    }

    /**
     * Add users by importing data from an excel file.
     * @param String $file_path - the file path of the excel file
     * @param Array  $result - an array which contains the query flags
     */
    public function add_users($file_path, &$result)
    {
        $this->__CI->load->library('lib_excel');
        $data = $this->__CI->lib_excel->get_data_from_excel($file_path);

        $number_of_records = count($data);
        $result['is_query_successful'] = true;
        for ( $i = 1; $i < $number_of_records; ++ $i ) {
            $user_information = $this->get_user_information_array($data[$i]);
            $query_result = $this->add_user($user_information);
            $result['is_query_successful']  &= $query_result['is_successful'];
            if ( $query_result['is_successful'] ) {
                $result['success_message']  .= $user_information['student_name'].'的信息已成功导入.<br />';
            } else {
                $result['error_message']    .= $user_information['student_name'].'的信息未能成功导入.<br />';
            }
        }
        return $result['is_query_successful'];
    }

    /**
     * Get information of the user which read from an excel file to 
     * a row array.
     * @param  Array $record - an array contains user's information
     * @return an array which contains the information of the user
     */
    private function get_user_information_array(&$record)
    {
        $user_information = array(
                'student_id'    => (string)$record[0],
                'student_name'  => (string)$record[1],
                'grade'         => (int)$record[3],
                'class'         => (int)$record[4],
                'room'          => (string)$record[5],
                'password'      => (string)$record[2]
            );
        return $user_information;
    }

    /**
     * Handle users' editing profile requests.
     * @param  String $student_id - the id of the student
     * @param  String $mobile - the mobile phone number of the student
     * @param  String $email - the email address of the student
     * @return an array which contains the query flags
     */
    public function edit_profile($student_id, $mobile, $email)
    {
        $result = array(
                'is_successful'     => false,  'is_query_successful'    => false,
                'is_mobile_empty'   => true,   'is_mobile_legal'        => false,
                'is_email_empty'    => true,   'is_email_legal'         => false
            );

        $result['is_successful'] = $this->verify_contact_information($mobile, $email, $result);
        if ( $result['is_successful'] ) {
            $contact_information = array(
                    'student_id'    => $student_id,
                    'mobile'        => $mobile,
                    'email'         => $email
                );
            $result['is_query_successful'] = $this->__CI->Students_model->update($contact_information);
        }
        $result['is_successful'] &= $result['is_query_successful'];

        return $result;
    }

    /**
     * Verify if all fields are legal.
     * @param  String $mobile - the mobile phone number of the student
     * @param  String $email - the email address of the student
     * @param  Array  $result - an array which contains the query flags 
     * @return an array which contains the query flags
     */
    private function verify_contact_information($mobile, $email, &$result)
    {
        $result['is_mobile_empty'] = empty($mobile);
        $result['is_mobile_legal'] = $this->is_mobile_legal($mobile);
        $result['is_email_empty']  = empty($email);
        $result['is_email_legal']  = $this->is_email_legal($email);

        $result['is_successful']   = (!$result['is_mobile_empty'] && !$result['is_email_empty'] &&
                                       $result['is_mobile_legal'] && $result['is_email_legal']);

        return $result['is_successful'];
    }

    /**
     * Verify if the mobile is legal.
     * @param  String  $mobile - the mobile to verify
     * @return true if the mobile is legal
     */
    private function is_mobile_legal($mobile)
    {
        return preg_match('/^[1-9][0-9]{10}$/', $mobile);
    }

    /**
     * Verify if the email is legal.
     * @param  String  $email - the email address to verify
     * @return true if the email address is legal
     */
    private function is_email_legal($email)
    {
        return preg_match('/^[A-Z0-9._%-]{4,18}@[A-Z0-9.-]+\.[A-Z]{2,4}$/i', $email);
    }

    /**
     * Handle users' changing password requests.
     * @param  String  $username - the username of the user
     * @param  String  $old_password - the password the user used before
     * @param  String  $new_password - the new password the user will use
     * @param  String  $confirm_password - confirm the new password to use
     * @return an array which contains the query flags
     */
    public function change_password($username, $old_password, $new_password, $confirm_password)
    {
        $result = array(
                'is_successful'                 =>  false,  'is_old_password_empty'     =>  true,
                'is_old_password_correct'       =>  false,  'is_new_password_empty'     =>  true,
                'is_new_password_length_legal'  =>  false,  'is_password_again_empty'   =>  true,
                'is_password_again_matched'     =>  false
            );

        $result['is_successful'] = $this->verify_passwords($old_password, $new_password, $confirm_password, $result);
        if ( $result['is_successful'] ) {
            $result['is_old_password_correct']  = $this->is_password_legal($old_password);
            $result['is_old_password_correct'] &= $this->__CI->Users_model->change_password($username, $old_password, $new_password);          
        }

        $result['is_successful'] &= $result['is_old_password_correct'];
        return $result;
    }

    /**
     * Verify if all fields are legal.
     * @param  String  $old_password - the password the user used before
     * @param  String  $new_password - the new password the user will use
     * @param  String  $confirm_password - confirm the new password to use
     * @param  Array   $result - an array which contains the query flags
     * @return an array which contains the query flags
     */
    private function verify_passwords($old_password, $new_password, $confirm_password, &$result)
    {
        $result['is_old_password_empty']        = empty($old_password);
        $result['is_new_password_empty']        = empty($new_password);
        $result['is_password_again_empty']      = empty($confirm_password);
        $result['is_new_password_length_legal'] = $this->is_password_legal($confirm_password);
        $result['is_password_again_matched']    = ( $new_password == $confirm_password );
        $result['is_successful'] = !( $result['is_old_password_empty'] || $result['is_new_password_empty'] ||
                                      $result['is_password_again_empty'] || !$result['is_new_password_length_legal'] ||
                                     !$result['is_password_again_matched'] );

        return $result['is_successful'];
    }

    /**
     * Verify if the password is legal.
     * @param  String $password - the password to verify
     * @return true if the password is legal
     */
    private function is_password_legal($password)
    {
        return (strlen($password) >= 6 && strlen($password) <= 16);
    }

    /**
     * Handle edting user's profile requests.
     * IMPORTANT: This function can only be used by administrator.
     * 
     * @param  String  $student_id - the student id of the student
     * @param  [type]  $profile    [description]
     * @param  [type]  $result     [description]
     * @return an array which contains the query flags
     */
    public function edit_user_profile($student_id, $profile, &$result)
    {
        $result = array(
                'is_successful'         => false,   'is_query_successful'   => false,
                'is_student_name_empty' => true,    'is_student_name_legal' => false,
                'is_grade_empty'        => true,    'is_grade_legal'        => false,
                'is_class_empty'        => true,    'is_class_legal'        => false,
                'is_room_empty'         => true,    'is_room_legal'         => false,
                'is_mobile_legal'       => false,   'is_email_legal'        => false,
                'is_password_legal'     => false
            );
        $result['is_successful']  = $this->verify_profile_information($profile, $result);
        if ( $result['is_successful'] ) {
            $result['is_query_successful'] = $this->update_user_profile($student_id, $profile);
            $result['is_successful']      &= $result['is_query_successful'];
        }

        return $result;
    }

    /**
     * Verify if basic information is legal.
     * IMPORTANT: This function can only be used by administrator.
     * 
     * @param  Array $profile - the information of a certain student
     * @param  Array $result - an array which contains the query flags 
     * @return an array which contains the query flags
     */
    private function verify_profile_information($profile, &$result)
    {
        $result['is_student_name_empty'] = empty($profile['student_name']);
        $result['is_student_name_legal'] = ( mb_strlen($profile['student_name'], 'utf-8') <= 24 );
        $result['is_grade_empty']        = empty($profile['grade']);
        $result['is_grade_legal']        = preg_match('/^[0-9]{4}$/', $profile['grade']);
        $result['is_class_empty']        = empty($profile['class']);
        $result['is_class_legal']        = preg_match('/^[0-9]{1,2}$/', $profile['class']);
        $result['is_room_empty']         = empty($profile['room']);
        $result['is_room_legal']         = preg_match('/^[0-9]{1,2}#[S,N][0-9]{3}$/', $profile['room']);
        $result['is_mobile_legal']       = (empty($profile['mobile'])   || $this->is_mobile_legal($profile['mobile']));
        $result['is_email_legal']        = (empty($profile['email'])    || $this->is_email_legal($profile['email']));
        $result['is_password_legal']     = (empty($profile['password']) || $this->is_password_legal($profile['password']));

        $result['is_successful'] = !$result['is_student_name_empty'] && $result['is_student_name_legal'] &&
                                   !$result['is_grade_empty']         && $result['is_grade_legal']        &&
                                   !$result['is_class_empty']         && $result['is_class_legal']        &&
                                   !$result['is_room_empty']          && $result['is_room_legal']         &&
                                    $result['is_mobile_legal']        && $result['is_email_legal']        &&
                                    $result['is_password_legal'];
        return $result['is_successful'];
    }

    /**
     * [update_user_profile description]
     * @param  String $student_id - the student id of the student
     * @param  Array  $profile - the profile information of the student
     * @return true if the query is successful
     */
    private function update_user_profile($student_id, $profile)
    {
        $is_successful = true;
        $basic_information = array(
                'student_id'        =>  $student_id,
                'student_name'      =>  $profile['student_name'],
                'grade'             =>  $profile['grade'],
                'class'             =>  $profile['class'],
                'user_groups_id'    =>  $this->get_user_group_id($profile['user_group_name']),
                'room'              =>  $profile['room'],
                'mobile'            =>  $profile['mobile'],
                'email'             =>  $profile['email']
            );
        $is_successful &= $this->__CI->Students_model->update($basic_information);

        if ( !empty($profile['password']) ) {
            $account_information = array(
                    'username'  =>  $student_id,
                    'password'  =>  $profile['password']
                );
            $is_successful &= $this->__CI->Users_model->update($account_information);
        }

        return $is_successful;
    }

    /**
     * Get the id of a certain user group by its name.
     * @param  String $group_name - the name of the user group
     * @return the id of the user group
     */
    private function get_user_group_id($group_name)
    {
        $user_group = $this->__CI->User_groups_model->get_user_group_id($group_name);
        return $user_group['group_id'];
    }

    /**
     * Handle Deleting a user's account requests.
     * IMPORTANT: This function can only be used by administrator.
     * 
     * @param  String $username - the username of the user
     * @return true if the query is successful
     */
    public function delete_account($username)
    {
        $this->__CI->load->model('Assessment_model');

        $is_successful = true;
        $is_successful &= $this->__CI->Users_model->delete($username);
        $is_successful &= $this->__CI->Students_model->delete($username);
        $is_successful &= $this->__CI->Assessment_model->delete($username);

        // TO DO: Delete other data in other tables which haven't create yet.
        return $is_successful;
    }

    /**
     * Handle deleting users' account in a certain grade.
     * IMPORTANT: This function can only be used by administrator.
     * 
     * @param  String $username - the username of the user
     * @return true if the query is successful
     */
    public function delete_accounts($grade)
    {
        $students = $this->get_students_profile_list($grade);
        $is_successful = true;

        foreach ( $students as $student ) {
            $is_successful &= $this->delete_account($student['student_id']);
        }

        return $is_successful;
    }
}

/* End of file lib_accounts.php */
/* Location: ./application/libraries/lib_accounts.php */