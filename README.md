#ReFill
A PHP/Redis library for caching auto-complete queries.

####Dependancies:
Uses the [Predis](https://github.com/nrk/predis) Client
    
###Usage:
Instantiate:
```php
$connection = new Predis\Client([
    "scheme" => "tcp",
    "host"   => "127.0.0.1",
    "port"   => 6379
]);

$refill = new ReFill($connection);
```

Cache a collection:  
```php
$refill->catalog('names', ReFillCollection::fromArray($list));
```

Find a match:  
```php
$refill->match('names', $term, $maxNumberOfResults)
```

#TODO:
    - revisit unique id
    - invalidate cache
    - improve test coverage
    - renaming/refactoring
    - multiple fragment search (redis intersect)
    - add exceptions/error handling
    - add a filterable attribute to allow results to be filtered by an intersect
