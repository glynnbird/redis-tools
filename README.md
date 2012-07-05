redis-tools
===========

Redis command-line tools to allow mass export, import and deletion of keys

## Introduction

Redis doesn't come with any tools to allow groups of keys to be imported and exported. You can build a database, save it (as dump.rdb) and use it as another Redis instance's database, but sometimes you just want to export a group of keys from one database (e.g. your staging environment) and apply them to another database (e.g. production).

Requirements:
* PHP with Redis extension

N.B this only works with simple key/value pairs e.g. not hashes or other higher order Redis data types.

## Redis export

This tool exports a group of single key/value pairs to stdout. The output format is the plain text protocol used by Redis e.g.

```
  # export all keys starting with ABC to stdout
  ./redis_export.php "ABC*"  
  
  # export keys with Lookup in the key, to a file
  ./redis_export.php "*ABC* > text.txt
  
  # export all keys to a file
  ./redis_export.php "*" > test.txt
  
```

## Redis import

Data exported using "redis_export.php", can be imported directly into a Redis server:

```
  # apply the data stored in test.txt to the local Redis server
  cat test.txt | ./redis_import.php
```

B.t.w redis_import.php is a one-liner. It basically uses "nc" to pipe the data to the correct port. If you have a modern version of Redis, you can use:

```
  cat test.txt | redis-cli --pipe
```

which will give you feedback of whether your commands worked or not, whereas redis_import.php does not.

## Redis delete

To delete a range of keys:

```
  # delete all keys starting with ABC
  ./redis_delete.php 'ABC*'
```
