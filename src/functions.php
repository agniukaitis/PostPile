<?php
/**
 * Bootstrapping functions, essential and needed for Anax to work together with some common helpers. 
 *
 */



/**
 * Utility for debugging.
 *
 * @param mixed $array values to print out
 *
 * @return void
 */
function dump($array) 
{
    echo "<pre>" . htmlentities(print_r($array, 1)) . "</pre>";
}



/**
 * Sort array but maintain index when compared items are equal.
 * http://www.php.net/manual/en/function.usort.php#38827
 *
 * @param array    &$array       input array
 * @param callable $cmp_function custom function to compare values
 *
 * @return void
 *
 */
function mergesort(&$array, $cmp_function) 
{
    // Arrays of size < 2 require no action.
    if (count($array) < 2) return;
    // Split the array in half
    $halfway = count($array) / 2;
    $array1 = array_slice($array, 0, $halfway);
    $array2 = array_slice($array, $halfway);
    // Recurse to sort the two halves
    mergesort($array1, $cmp_function);
    mergesort($array2, $cmp_function);
    // If all of $array1 is <= all of $array2, just append them.
    if (call_user_func($cmp_function, end($array1), $array2[0]) < 1) {
        $array = array_merge($array1, $array2);
        return;
    }
    // Merge the two sorted arrays into a single sorted array
    $array = array();
    $ptr1 = $ptr2 = 0;
    while ($ptr1 < count($array1) && $ptr2 < count($array2)) {
        if (call_user_func($cmp_function, $array1[$ptr1], $array2[$ptr2]) < 1) {
            $array[] = $array1[$ptr1++];
        } else {
            $array[] = $array2[$ptr2++];
        }
    }
    // Merge the remainder
    while ($ptr1 < count($array1)) $array[] = $array1[$ptr1++];
    while ($ptr2 < count($array2)) $array[] = $array2[$ptr2++];
    return;
}

// Time format is UNIX timestamp or
// PHP strtotime compatible strings
function dateDiff($time1, $time2, $precision = 1) {
  // If not numeric then convert texts to unix timestamps
  if (!is_int($time1)) {
    $time1 = strtotime($time1);
  }
  if (!is_int($time2)) {
    $time2 = strtotime($time2);
  }

  // If time1 is bigger than time2
  // Then swap time1 and time2
  if ($time1 > $time2) {
    $ttime = $time1;
    $time1 = $time2;
    $time2 = $ttime;
  }

  // Set up intervals and diffs arrays
  $intervals = array('year','month','day','hour','min','sec');
  $diffs = array();

  // Loop thru all intervals
  foreach ($intervals as $interval) {
    // Create temp time from time1 and interval
    $ttime = strtotime('+1 ' . $interval, $time1);
    // Set initial values
    $add = 1;
    $looped = 0;
    // Loop until temp time is smaller than time2
    while ($time2 >= $ttime) {
      // Create new temp time from time1 and interval
      $add++;
      $ttime = strtotime("+" . $add . " " . $interval, $time1);
      $looped++;
    }

    $time1 = strtotime("+" . $looped . " " . $interval, $time1);
    $diffs[$interval] = $looped;
  }
  
  $count = 0;
  $times = array();
  // Loop through all diffs
  foreach ($diffs as $interval => $value) {
    // Break if we have needed precission
    if ($count >= $precision) {
       break;
    }
    // Add value and interval 
    // if value is bigger than 0
    if ($value > 0) {
       // Add s if value is not 1
       if ($value != 1) {
         $interval .= "s";
       }
       // Add value and interval to times array
       $times[] = $value . " " . $interval;
       $count++;
    }
  }

  if($times == null) {
    // Return base string
    return "just now";
  }
  else {
    // Return string with times
    $time = implode(", ", $times) . " ago";
    return $time;
  }
}

function getExtract($string)
{
  $string = strip_tags($string);
  if(strlen($string) > 197) {
    $string = substr($string, 0, 197)." ...";
    
    return $string;
  }
  else {
    return $string;
  }
}