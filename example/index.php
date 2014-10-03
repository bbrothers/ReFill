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

$refill = new ReFill($connection);

if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    if(! empty($_GET['term'])) {
        header('Content-Type: application/json');
        echo json_encode($refill->match('names', $_GET['term']));
        exit();
    }
}

$faker = Faker\Factory::create();

$list = [];

for ($i = 0; $i < 1000; $i++) {
    $list[] = ['id' => $i, 'name' => $faker->name];
}

$refill->catalog('names', ReFillCollection::fromArray($list));

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
                    url : "/",
                    dataType : 'json',
                    data : function (term) {
                        return {
                            term : term
                        };
                    },
                    results : function (results) {
                        return {
                            results : results.map(function(item) {
                                return {
                                    id : item.id,
                                    text : item.name
                                };
                            })
                        }
                    }
                }
            });
        </script>
    </body>
</html>
