#!/usr/bin/php -q
<?php
  
  $argc = $_SERVER["argc"];
  $argv = $_SERVER["argv"];

  if($argc !=2) {
    die("Usage ./redis_export.php <pattern>\n".
        " e.g. ./redis_export.php 'KeyphraseLookup *'".
        "      \n");
  }
  $pattern = $argv[1];

  // connect to redis (locally)
  $red = new Redis();
  $red->connect("localhost", 6379);
  $red->setOption(Redis::OPT_SERIALIZER,Redis::SERIALIZER_NONE);

  // function to generate a key value "SET" command in Redis protocol
  function genRedis($key,$value) {
    $out = '*3'."\r\n";
    $out .= '$3'."\r\n";
    $out .= "SET\r\n";
    $out .= '$'.strlen($key)."\r\n".$key."\r\n";
    $out .= '$'.strlen($value)."\r\n".$value."\r\n";   
    return $out;
  }
  
  // get all the redis keys that need exporting
  $allkeys = $red->keys($pattern);

  // foreach key we find
  foreach($allkeys as $rediskey) {
    if($rediskey) {
      // get it
      $value = $red->get($rediskey);
      
      // convert it to raw redis protocol .... and print it out
      echo genRedis($rediskey,$value);
    }
  }
  
?>