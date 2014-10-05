<?php
/**
 * Autocomplete using Redis.
 *
 * @author Kirk Morales (kirk@kirkmorales.com)
 * @license MIT License
 */

header('Content-Type: application/json');

// If no search string, quit
if (!$_GET['s']) {
  echo '{"results":[]}';
  exit(0);
}

require 'redis.php';

/************************
 * Constants
 ************************/

// Max number of results to return
$COUNT        = 50;

// Number of matches to return per Redis query
$RANGE_LENGTH = 50;

// Name of redis key to use
$KEY          = ':rediskey';

// Redis host
$REDIS_HOST   = 'somehost';

// Redis port
$REDIS_PORT   = 6379;

/************************
 * Script
 ************************/

$redis = new Credis_Client("$REDIS_HOST:$REDIS_PORT");

$results = array();

$prefix = $_GET['s'];

$start = $redis->zrank($KEY, $prefix);
if (!is_numeric($start) || $start < 0) {
  echo '{"results":[]}';
  exit(0);
}

while (count($results) != $COUNT) {
  $range = $redis->zrange($KEY, $start, $start + $RANGE_LENGTH - 1);
  $start += $RANGE_LENGTH;
  
  if (!$range || count($range) == 0) {
    break;
  }

  foreach ($range as $entry) {
    $min_length = (strlen($entry) < strlen($prefix)) ? strlen($entry) : strlen($prefix);

    if (substr($entry, 0, $min_length) != substr($prefix, 0, $min_length)) {
      $COUNT = count($results);
    }

    if (substr($entry, strlen($entry)-1, 1) == '*' && count($results) != $COUNT) {
      array_push($results, substr($entry, 0, strlen($entry)-1));
    }
  }
}

echo json_encode(array('results' => $results));

?>