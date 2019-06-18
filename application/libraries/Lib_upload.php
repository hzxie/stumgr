<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * The class handles all requests on uploading files.
 * It uses XMLHttpRequest to transfer files.
 * 
 * @author: Haozhe Xie <cshzxie@gmail.com>
 */
class Lib_upload {
    /**
     * [$config description]
     * @var [type]
     */
    private $config;
    
    /**
     * The contructor of the Account Library class.
     */
    public function __construct($config = array()) 
    {
        $this->config = $config;
    }

    /**
     * Upload a file to the server.
     * @return an array with a boolean flag which infers if the operation
     *         is successful, and an extra message.
     */
    public function do_upload()
    {
        $result = array(
                'is_successful' => true,
                'extra_message' => ''
            );

        if (!isset($_FILES["fileUploaderfile"])) {
            $result['is_successful']  = false;
            $result['extra_message'] = '请选择需要上传的文件.';
        } else if ( !$this->is_file_type_allowed($_FILES["fileUploaderfile"]['name']) ) {
            $result['is_successful']  = false;
            $result['extra_message'] = '文件类型不匹配.';
        } else if ( !$this->is_file_size_allowed($_FILES["fileUploaderfile"]['size']) ) {
            $result['is_successful']  = false;
            $result['extra_message'] = '超过最大允许的文件大小.';   
        }

        if ( $result['is_successful'] ) {
            $result = $this->upload_file($_FILES["fileUploaderfile"]['name'], 
                                         $_FILES["fileUploaderfile"]['tmp_name']);
        }
        return $result;
    }

    /**
     * Verify if the type of the file is allowed to upload.
     * @param  String  $file_name - the name of the file
     * @return true if the type of the file is allowed to upload
     */
    private function is_file_type_allowed($file_name)
    {
        $ext  = pathinfo($file_name, PATHINFO_EXTENSION);
        $exts = explode('|', $this->config['allowed_types']);

        if ( in_array($ext, $exts) ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Verify if the size of the file is beyond the MAX_SIZE defined
     * in the configuration.
     * @param  int  $file_size - the size of the file
     * @return true if the size of the file is allowed
     */
    private function is_file_size_allowed($file_size)
    {
        if ( $this->config['max_size'] != 0 ) {
            return ( $file_size <= ($this->config['max_size'] * 1024) );
        }
        return true;
    }

    /**
     * [upload_file description]
     * @return [type] [description]
     */
    private function upload_file($file_name, $tmp_name)
    {
        $result = array(
                'is_successful' => true,
                'extra_message' => ''
            );

        $source_path = $_FILES["fileUploaderfile"]['tmp_name'];
        $target_path = $this->get_target_file_path($file_name, $tmp_name);
        if ( $result['is_successful'] = move_uploaded_file($tmp_name, $target_path) ) {
            $result['extra_message'] = $target_path;
        } else {
            $result['extra_message'] = '发生未知错误.<br />';
        }

        return $result;
    }

    /**
     * [get_target_file_path description]
     * @param  [type] $file_name [description]
     * @param  [type] $tmp_name  [description]
     * @return [type]            [description]
     */
    private function get_target_file_path($file_name, $tmp_name)
    {
        mt_srand();
        $ext = pathinfo($file_name, PATHINFO_EXTENSION);
        $random_file_name = md5(uniqid(mt_rand())).'.'.$ext;

        return $this->config['upload_path'].$random_file_name;
    }  
}

/* End of file lib_upload.php */
/* Location: ./application/libraries/lib_upload.php */