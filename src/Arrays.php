<?php

namespace Battis\DataUtilities;

class Arrays
{
    /**
     * Load a CSV file into an associative array
     *
     * @param string $path Path to the CSV file
     * @param boolean $firstRowLabels (Optional, default `TRUE`)
     *
     * @return string[][]|boolean A two-dimensional array of string values, if the
     *        `$field` contains a CSV file, `FALSE` if there is no file
     **/
    public static function fromCSV(string $path, $firstRowLabels = true)
    {
        $result = false;
        if (!empty($path)) {
            /* open the file for reading */
            $csv = fopen($path, 'r');
            $result = array();

            /* treat the first row as column labels */
            if ($firstRowLabels) {
                $fields = fgetcsv($csv);
            }

            /* walk through the file, storing each row in the array */
            while ($csvRow = fgetcsv($csv)) {
                $row = array();

                /* if we have column labels, use them */
                if ($firstRowLabels) {
                    foreach ($fields as $i => $field) {
                        if (isset($csvRow[$i])) {
                            $row[$field] = $csvRow[$i];
                        }
                    }
                } else {
                    $row = $csvRow;
                }

                /* append the row to the array */
                $result[] = $row;
            }
            fclose($csv);
        }
        return $result;
    }
}
