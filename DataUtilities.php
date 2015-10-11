<?php

/** DataUtilities and related classes */

namespace Battis;

class DataUtilities {
	
	/**
	 * Converts $title to Title Case, and returns the result
	 *
	 * @param string $title
	 *
	 * @return string
	 *
	 * @see http://www.sitepoint.com/title-case-in-php/ SitePoint
	 **/
	public static function titleCase($title) {
	
		/* Our array of 'small words' which shouldn't be capitalised if they aren't the first word.  Add your own words to taste. */
		$lowerCaseWords = array(
			'of','a','the','and','an','or','nor','but','is','if','then','else','when',
			'at','from','by','on','off','for','in','out','over','to','into','with'
		);
		
		$allCapsWords = array(
			'i', 'ii', 'iii', 'iv', 'v', 'vi', 'sis', 'csv', 'php', 'html'	
		);
		
		$camelCaseWords = array(
			'github' => 'GitHub'
		);
		
		/* add a space after each piece of punctuation */
		$title = preg_replace('/([^a-z0-9])\s*/i', '$1 ', strtolower($title));
		
		// TODO smart em- and en-dashes
		// TODO superscripts for 1st, 2nd, 3rd
		
		/* Split the string into separate words */
		$words = explode(' ', $title);
		
		foreach ($words as $key => $word) {
			
			if (in_array($word, $allCapsWords)) {
				$words[$key] = strtoupper($word);
			} elseif (array_key_exists($word, $camelCaseWords)) {
				$words[$key] = $camelCaseWords[$key];
			} elseif ($key == 0 or !in_array($word, $lowerCaseWords)) {
				$words[$key] = ucwords($word);
			}
		}
		
		/* Join the words back into a string */
		$newtitle = implode(' ', $words);
		
		return $newtitle;
	}
	
	/**
	 * Load an uploaded CSV file into an associative array
	 *
	 * @param string $field Field name holding the file name
	 * @param boolean $firstRowLabels (Optional) Default `TRUE`
	 *
	 * @return string[][]|boolean A two-dimensional array of string values, if the
	 *		`$field` contains a CSV file, `FALSE` if there is no file
	 **/
	public static function loadCsvToArray($field, $firstRowLabels = true) {
		$result = false;
		if(!empty($_FILES[$field]['tmp_name'])) {
	
			/* open the file for reading */
			$csv = fopen($_FILES[$field]['tmp_name'], 'r');
			$result = array();
			
			/* treat the first row as column labels */
			if ($firstRowLabels) {
				$fields = fgetcsv($csv);
			}
			
			/* walk through the file, storing each row in the array */
			while($csvRow = fgetcsv($csv)) {
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
	
?>