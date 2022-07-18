<?php

require __DIR__ . '/../vendor/autoload.php';


use mon\store\Rdb;

class App
{
    private $listKey = 'list';

    public function run()
    {
        // $test = Rdb::instance()->get('test');
        // var_dump($test);

        // for ($i = 0; $i < 10; $i++) {
        //     $num = mt_rand(0, 100);
        //     $str = 'mon_list_' . $i;
        //     Rdb::instance()->zAdd($this->listKey, $num, $str);
        // }

        // Rdb::instance()->zAdd($this->listKey, 101, 'mon_list_2');

        // $data = Rdb::instance()->zRevRange($this->listKey, 0, -1);
        $data = Rdb::instance()->zRange($this->listKey, 0, -1, true);
        var_dump($data);
    }
}


(new App)->run();


