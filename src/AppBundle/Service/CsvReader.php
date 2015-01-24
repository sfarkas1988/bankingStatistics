<?php
/**
 * Created by PhpStorm.
 * User: farkas-berlin
 * Date: 06.12.14
 * Time: 20:21
 */

namespace AppBundle\Service;


class CsvReader
{

    private $csvFilePath;

    private $rows = array();

    public function __construct($csvFilePath)
    {
        $this->csvFilePath = $csvFilePath;
    }

    public function getRows($ignoreFirstLine = true)
    {

        if (!empty($this->rows)) {
            return $this->rows;
        }

        if (!file_exists($this->csvFilePath)) {
           throw new \Exception(
               'Csv File not found. Please setup a valid file for the given csv file: '. $this->csvFilePath
           );
        }

        if (($handle = fopen($this->csvFilePath, "r")) !== false) {
            $i = 0;
            while (($data = fgetcsv($handle, null, ";")) !== false) {
                $i++;
                if ($ignoreFirstLine && $i == 1) {
                    continue;
                }
                $this->rows[] = $data;
            }
            fclose($handle);
        }
        return $this->rows;
    }
}