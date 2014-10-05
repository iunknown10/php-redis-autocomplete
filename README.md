PHP Autocomplete using Redis
======================

The files included in this repository allow one to create an autocomplete feature which utilizing Redis. This is a PHP extension of the following article: http://oldblog.antirez.com/post/autocomplete-with-redis.html

## Create Redis Database

Create a Redis database and update the connection information in both `load.php` and `search.php`.

## Load Data

To load data into Redis, create a file where each line is a string that can be searched using autocomplete. Update `load.php` to point to this file, and then execute it:

**From a web browser or Curl**: 
`http://myhost.com/load.php`

**From the command line**: 
`php load.php`

This will populate the Redis database with all strings contained in the file, as well as partials which is what makes the autocomplete work.

## Search Strings

Call `search.php` with an `s` parameter, specifying the partial string to use for searching. For example, given the following data set:

```shell
apply
apple
applicant
banana
carrot
```

Calling `GET /search.php?s=ap` will yield the following result:

```json
{ "results": ["apply","apple","applicant"] }
```

## License

MIT License
