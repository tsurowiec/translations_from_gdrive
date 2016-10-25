<?php


class ConvertCsv2Json
{
    public static function convert($csv)
    {
        $file = fopen('data.csv', 'w');
        fwrite($file, $csv);
        fclose($file);

        $file = fopen('data.csv', 'r');
        foreach (range(1, 5) as $x) {
            fgetcsv($file);
        }
        $lines = $en = $ar = $n = [];
        while ($line = fgetcsv($file)) {
            $lines[] = $line;
        }
        fclose($file);

        for ($i = 0; $i < count($lines); ++$i) {
            $l = $lines[$i];
            $level = self::level($l);
            $n[$level] = $l[$level-1];
            if ($i == count($lines) - 1 || self::isLeaf($lines[$i], $lines[$i + 1])) {
                switch ($level) {
                    case 1 :
                        $en[$n[1]] = $l[5];
                        $ar[$n[1]] = $l[6];
                        break;
                    case 2 :
                        $en[$n[1]][$n[2]] = $l[5];
                        $ar[$n[1]][$n[2]] = $l[6];
                        break;
                    case 3 :
                        $en[$n[1]][$n[2]][$n[3]] = $l[5];
                        $ar[$n[1]][$n[2]][$n[3]] = $l[6];
                        break;
                    case 4 :
                        $en[$n[1]][$n[2]][$n[3]][$n[4]] = $l[5];
                        $ar[$n[1]][$n[2]][$n[3]][$n[4]] = $l[6];
                        break;
                    case 5 :
                        $en[$n[1]][$n[2]][$n[3]][$n[4]][$n[5]] = $l[5];
                        $ar[$n[1]][$n[2]][$n[3]][$n[4]][$n[5]] = $l[6];
                        break;
                }
            }
        }

        $file = fopen('/lang/en.json', 'w');
        fwrite($file, json_encode($en, JSON_PRETTY_PRINT));
        fclose($file);

        $file = fopen('/lang/ar.json', 'w');
        fwrite($file, json_encode($ar, JSON_PRETTY_PRINT));
        fclose($file);

        echo 'done...';
    }

    private static function isLeaf($current, $next)
    {
        return self::level($current) >= self::level($next);
    }

    private static function level($line)
    {
        $levels = 0;
        if ($line[0]) {
            $levels = 1;
        }
        if ($line[1]) {
            $levels = 2;
        }
        if ($line[2]) {
            $levels = 3;
        }
        if ($line[3]) {
            $levels = 4;
        }
        if ($line[4]) {
            $levels = 5;
        }

        return $levels;
    }
}
