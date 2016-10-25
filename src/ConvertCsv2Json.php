<?php


class ConvertCsv2Json
{
    public static function convert($csv)
    {
        $file = fopen('data.csv', 'w');
        fwrite($file, $csv);
        fclose($file);

        $file = fopen('data.csv', 'r');
        $ar = $en = [];
        foreach(range(1,5) as $x) {
            fgetcsv($file);
        }
        $levels = 0;
        $l1 = $l2 = $l3 = $l4 = $l5 = '';
        while ($line = fgetcsv($file)) {
            if($line[0]) {
                $l1 = $line[0];
                $levels = 1;
            }
            if($line[1]) {
                $l2 = $line[1];
                $levels = 2;

            }
            if($line[2]) {
                $l3 = $line[2];
                $levels = 3;

            }
            if($line[3]) {
                $l4 = $line[3];
                $levels = 4;

            }
            if($line[4]) {
                $l5 = $line[4];
                $levels = 5;
            }
            if($line[5]) {
                $val = $line[5];
                switch ($levels) {
                    case 1 : $en[$l1] = $val; break;
                    case 2 : $en[$l1][$l2] = $val; break;
                    case 3 : $en[$l1][$l2][$l3] = $val; break;
                    case 4 : $en[$l1][$l2][$l3][$l4] = $val; break;
                    case 5 : $en[$l1][$l2][$l3][$l4][$l5] = $val; break;
                }
            }
            if($line[6]) {
                $val = $line[6];
                switch ($levels) {
                    case 1 : $ar[$l1] = $val; break;
                    case 2 : $ar[$l1][$l2] = $val; break;
                    case 3 : $ar[$l1][$l2][$l3] = $val; break;
                    case 4 : $ar[$l1][$l2][$l3][$l4] = $val; break;
                    case 5 : $ar[$l1][$l2][$l3][$l4][$l5] = $val; break;
                }
            }
        }
        fclose($file);

        $file = fopen('/lang/en.json', 'w');
        fwrite($file, json_encode($en, JSON_PRETTY_PRINT));
        fclose($file);

        $file = fopen('/lang/ar.json', 'w');
        fwrite($file, json_encode($ar, JSON_PRETTY_PRINT));
        fclose($file);

        echo('done...');
    }
}
