<?php

use Predis\Client as Redis;
use ReFill\ReFill;
use ReFill\ReFillCollection;

require '../vendor/autoload.php';

Predis\Autoloader::register();

$connection = new Redis([
    "scheme" => "tcp",
    "host"   => "127.0.0.1",
    "port"   => 6379
]);

$faker = Faker\Factory::create();

$list = [];

for ($i = 0; $i < 1000; $i++) {
    $list[] = ['id' => $i, 'name' => $faker->name];
}

$refill = new ReFill($connection);

$refill->cache('names', ReFillCollection::fromArray($list));

if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    if(! empty($_GET['term'])) {
        return json_encode($refill->match('names', $_GET['term']));
    }
}
?>
<!doctype html>
<html class="no-js" lang="">
    <head>
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/select2/3.5.0/select2.min.css">
        <script src="//code.jquery.com/jquery-2.1.1.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/select2/3.5.0/select2.min.js"></script>
    </head>
    <body>
        <input type="hidden" name="test" id="test" />
        <script>
            $("#test").select2({
                placeholder : "Search for a name",
                minimumInputLength : 1,
                ajax : {
                    url : "/example.php",
                    dataType : 'json',
                    data : function (term) {
                        return {
                            q : term
                        };
                    }, results : function (data, page) {
                        return {results : data};
                    }
                }
            });
        </script>
    </body>
</html>