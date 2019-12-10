<?php

/**
 * SortedNearest - An Class that consist the algorithm to make Nearest Value according to a number
 *
 * @author stackoverflow.com
 */

class SortedNearest {


	const ARRAY_NEAREST_DEFAULT = 0;
	const ARRAY_NEAREST_LOWER   = 1;
	const ARRAY_NEAREST_HIGHER  = 2;

	
	public function array_numeric_sorted_nearest($array, $value, $method = ARRAY_NEAREST_LOWER){

		$count = count($array);

	    if($count == 0) {
	        return null;
	    }    

	    $div_step               = 2;    
	    $index                  = ceil($count / $div_step);    
	    $best_index             = null;
	    $best_score             = null;
	    $direction              = null;    
	    $indexes_checked        = Array();

	    while(true) { 

	        if(isset($indexes_checked[$index])) {
	            break ;
	        }

	        $curr_key = $array[$index];
	        if($curr_key === null) {
	            break ;
	        }

	        $indexes_checked[$index] = true;

	        
	        if($curr_key == $value) {
	            return $curr_key;
	        }

	        $prev_key = $array[$index - 1];
	        $next_key = $array[$index + 1];

	        switch($method) {
	            default:
	            case ARRAY_NEAREST_DEFAULT:
	                $curr_score = abs($curr_key - $value);

	                $prev_score = $prev_key !== null ? abs($prev_key - $value) : null;
	                $next_score = $next_key !== null ? abs($next_key - $value) : null;

	                if($prev_score === null) {
	                    $direction = 1;                    
	                }else if ($next_score === null) {
	                    break 2;
	                }else{                    
	                    $direction = $next_score < $prev_score ? 1 : -1;                    
	                }
	                break;
	            case ARRAY_NEAREST_LOWER:
	                $curr_score = $curr_key - $value;
	                if($curr_score > 0) {
	                    $curr_score = null;
	                }else{
	                    $curr_score = abs($curr_score);
	                }

	                if($curr_score === null) {
	                    $direction = -1;
	                }else{
	                    $direction = 1;
	                }                
	                break;
	            case ARRAY_NEAREST_HIGHER:
	                $curr_score = $curr_key - $value;
	                if($curr_score < 0) {
	                    $curr_score = null;
	                }

	                if($curr_score === null) {
	                    $direction = 1;
	                }else{
	                    $direction = -1;
	                }  
	                break;
	        }

	        if(($curr_score !== null) && ($curr_score < $best_score) || ($best_score === null)) {
	            $best_index = $index;
	            $best_score = $curr_score;
	        }

	        $div_step *= 2;
	        $index += $direction * ceil($count / $div_step);
	    }

	    return $array[$best_index];

	}	

}

