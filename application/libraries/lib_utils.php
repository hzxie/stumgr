<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Utility class for the project.
 * @author: Xie Haozhe <zjhzxhz@gmail.com>
 */
class Lib_utils {
    /**
     * Verify if a value exists in multidimensional array.
     * @param  Array  $array - the array to find
     * @param  String $key   - the key to find
     * @param  mixed  $val   - the value to find
     * @return true if the value exists
     */
    public static function in_array(&$array, $key, $val)
    {
        if ( $array ) {
            foreach ( $array as $item ) {
                if ( isset($item[$key]) && $item[$key] == $val ) {
                    return true;
                }
            }            
        }
        return false;
    }

    /**
     * Add a column to an array.
     * @param Array $array  - the array to insert a column
     * @param String $key   - the key to insert
     * @param String $value - the value to insert
     * @return the array after inserting a column
     */
    public static function add_column_to_array(&$array, $key, $value)
    {
        if ( $array ) {
            foreach ( $array as &$row ) {
                $row[$key] = $value;
            }
            return $array;
        }
        return false;
    }
}

/* End of file lib_utils.php */
/* Location: ./application/libraries/lib_utils.php */