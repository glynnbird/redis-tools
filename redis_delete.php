#!/usr/bin/php -q
<?php
  
  $argc = $_SERVER["argc"];
  $argv = $_SERVER["argv"];

  if($argc !=2) {
    die("Usage ./redis_delete.php <pattern>\n".
        " e.g. ./redis_delete.php 'KeyphraseLookup *'".
        "      \n");
  }
  $pattern = $argv[1];

  // connect to redis (locally)
  $red = new Redis();
  $red->connect("localhost", 6379);
  $red->setOption(Redis::OPT_SERIALIZER,Redis::SERIALIZER_NONE);
  
  // get all the redis keys that need exporting
  $allkeys = $red->keys($pattern);
  
  // foreach key we find
  foreach($allkeys as $rediskey) {
    if($rediskey) {
      // delete it
      $red->del($rediskey);
    }
  }
  
  echo "Deleted ".count($allkeys)." keys\n";
  
?>