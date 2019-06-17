<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * The encapsulation of the PhpSpreadsheet Library.
 * 
 * @author Haozhe Xie <cshzxie@gmail.com>
 */
class Lib_excel { 
    /**
     * Get data from an excel file.
     * @param  String $file_path - the path of the excel file
     * @return an array contains data which is read from the
     *         excel file.
     */
    public function get_data_from_excel($file_path)
    {
        $reader = IOFactory::createReaderForFile($file_path);
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($file_path);
        $worksheet = $spreadsheet->getActiveSheet(); 
        $number_of_rows = $worksheet->getHighestRow();
        $number_of_columns = Coordinate::columnIndexFromString(
                                $worksheet->getHighestColumn());

        $data = array();
        for ( $row = 1; $row <= $number_of_rows; ++ $row ) {
        	for ( $column = 1; $column <= $number_of_columns; ++ $column ) {
        		$columnName = Coordinate::stringFromColumnIndex($column);
        		$data[$row - 1][$column - 1] = $worksheet->getCellByColumnAndRow($column, $row)->getValue();
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