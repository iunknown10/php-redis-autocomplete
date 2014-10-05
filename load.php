<?php
/**
 * Loads data into Redis.
 *
 * @author Kirk Morales (kirk@kirkmorales.com)
 * @license MIT License
 */

require 'redis.php';

/************************
 * Constants
 ************************/

// Name of redis key to use
$KEY          = ':rediskey';

// Redis host
$REDIS_HOST   = 'somehost';

// Redis port
$REDIS_PORT   = 6379;

$CSV_FILE     = '/path/to/csv';

/************************
 * Script
 ************************/

$redis = new Credis_Client("$REDIS_HOST:$REDIS_PORT");

// Open the file
$handle = fopen($CSV_FILE, 'r');
if ($handle) {
  while (($line = fgets($handle)) !== false) {
    for ($i=0;$i<strlen($line);$i++) {
      $redis->zadd($KEY, 0, substr($line, 0, $i));
    }
    $redis->zadd($KEY, 0, $line . '*');
  }
} else {
  echo "Could not open file: $!\n";
} 
fclose($handle);

?>