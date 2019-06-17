<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH."/third_party/PHPExcel.php"; 

/**
 * The encapsulation of the PHPExcel Library.
 * 
 * @author Haozhe Xie <cshzxie@gmail.com>
 */
class Lib_excel extends PHPExcel { 
	/**
	 * The constructor of the class.
	 */
    public function __construct() 
	{ 
        parent::__construct();
    }

    /**
     * Get data from an excel file.
     * @param  String $file_path - the path of the excel file
     * @return an array contains data which is read from the
     *         excel file.
     */
    public function get_data_from_excel($file_path)
    {
        if ( substr( $file_path, -3 ) == 'xls' ) {
            $reader = PHPExcel_IOFactory::createReader( 'Excel5' );
        } else {
            $reader = PHPExcel_IOFactory::createReader( 'Excel2007' );
        }
        
        $obj = $reader->load($file_path);
        $sheet = $obj->getSheet(0); 
        $number_of_rows = $sheet->getHighestRow();
        $number_of_columns = PHPExcel_Cell::columnIndexFromString($sheet->getHighestColumn());

        $data = array();
        for ( $row = 1; $row <= $number_of_rows; ++ $row ) {
        	for ( $column = 0; $column < $number_of_columns; ++ $column ) {
        		$columnName = PHPExcel_Cell::stringFromColumnIndex($column);
        		$data[$row - 1][$column] = $sheet->getCellByColumnAndRow($column, $row)->getValue();
        	}
        }
        return $data;
    }
	
	/**
	 * Convert the datetime format from Excel to PHP.
	 * @param  String $days - datetime format in Excel
	 * @return a datetime format in PHP
	 */
	public function excel2timestamp($days)
	{
		// But you must subtract 1 to get the correct timestamp 
		$ts = mktime(0, 0, 0, 1, $days - 1, 1900); 
		// So, this would then match Excel's representation: 
		return date("Y-m-d", $ts);
	}
	
}

/* End of file lib_excel.php */
/* Location: ./application/libraries/lib_excel.php */