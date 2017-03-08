<?php

namespace XHG\CoreBundle\Services;

/**
 * Description of ConvertCsvToArry
 *
 * @author xhg
 */
class ConvertCsvToArray
{

    public function __construct()
    {
        
    }

    public function convert($filename, $delimiter = ';')
    {
        if (!file_exists($filename) || !is_readable($filename)) {
            return FALSE;
        }
        
        $header = NULL;
        $data = array();
        $key = 1;

        if (($handle = fopen($filename, 'r')) !== FALSE) {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE) {
                if (!$header) {
                    $header = $row;
                } else {
                    $data[$key++] = array_combine($header, $row);
                }
            }
            fclose($handle);
        }
        return $data;
    }

}
