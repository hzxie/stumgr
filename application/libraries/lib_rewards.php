<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * The class handles all requests about rewards.
 * @author: Xie Haozhe <zjhzxhz@gmail.com>
 */
class Lib_rewards {
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
		$this->__CI->load->model('Rewards_model');
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
     * Get available years to select from existing data.
     *
     * The function is mainly used for students to query their reward records 
     * from the database. So the available years should not earlier than when 
     * they attend university.
     *
     * @param String  student_id - the student id of the student
     * @return an array contains all available years
     */
	public function get_available_years($student_id)
	{
		$available_years	= $this->__CI->Rewards_model->get_available_years($student_id);
		return $available_years;
	}

	/**
     * Get available years to select from existing data.
     *
     * The function is mainly used for administrators to get all available
     * years for reward records in the database.
     * 
     * @return an array contains all available years
     */
	public function get_all_available_years()
	{
		$available_years	= $this->__CI->Rewards_model->get_all_available_years();
		return $available_years;
	}

	/**
	 * Get all reward levels.
	 * @return an array which contains all reward levels
	 */
	public function get_reward_levels()
	{
		$reward_levels		= $this->__CI->Rewards_model->get_reward_levels();
		return $reward_levels;
	}

	/**
     * Handle students' getting reward records requests.
     * @param  int $school_year - the year to query
     * @return an array contains reward records with query flags
     */
	public function get_reward_records_by_students($school_year, $student_id)
	{
		$reward_records		= $this->__CI->Rewards_model->get_reward_records_by_students($school_year, $student_id);
		return $reward_records;
	}

	/**
     * Handle students' adding reward records requests.
     * @return an array which contains the query flags
     */
	public function add_reward_record($student_id, $reward_level_id, $detail, $additional_score)
	{
		$result = array(
				'is_successful'				=> false,
				'is_detail_empty'			=> empty($detail),
				'is_detail_legal'			=> $this->is_detail_legal($detail),
				'is_additional_score_empty'	=> empty($additional_score),
				'is_additional_score_legal'	=> $this->is_additional_score_legal($additional_score),
				'is_query_successful'		=> false
			);

		$result['is_successful'] = !$result['is_detail_empty'] && $result['is_detail_legal'] &&
								   !$result['is_additional_score_empty'] && $result['is_additional_score_legal'];
		if ( $result['is_successful'] ) {
			$reward_record = array(
				'school_year'				=> $this->get_current_school_year(),
				'student_id'				=> $student_id,
				'reward_level_id'			=> $reward_level_id,
				'detail'					=> $detail,
				'additional_score'			=> $additional_score
			);
			$result['is_query_successful'] 	= $this->__CI->Rewards_model->insert($reward_record);
		}
		$result &= $result['is_query_successful'];
		return $result;
	}

	/**
	 * Verify if the detail information of a reward record is legal.
	 * @param  String  $detail -the detail information of a reward record
	 * @return true if the detail information of a reward record is legal
	 */
	private function is_detail_legal($detail)
	{
		return strlen($detail < 256);
	}

	/**
	 * Veryfy if the additional score is legal
	 * @param  float $additional_score - the additional score of a reward
	 * @return true if the additional score is legal
	 */
	private function is_additional_score_legal($additional_score)
	{
		return preg_match('/^[-+]?[0-9]*\.?[0-9]+$/', $additional_score);
	}
}

/* End of file lib_rewards.php */
/* Location: ./application/libraries/lib_rewards.php */