<?php

function bSearch($file, $find_key)
{
    $f = fopen($file, 'r');
    
    while (! feof($f)) {
        
        $s = fgets($f, 4000);
        mb_convert_encoding($s, 'UTF-8');
        $es = explode('\x0A', $s);
        array_pop($es);
        
        foreach ($es as $key => $value) {
            $arr[] = explode('\t', $value);
        }
        
        $start = 0;
        $end = count($arr) - 1;
        
        while ($start <= $end) {
            $half = floor(($start + $end) / 2);
            $strnatcmp = strnatcmp($arr[$half][0], $find_key);
            if ($strnatcmp > 0) {
                $end = $half - 1;
            } elseif ($strnatcmp < 0) {
                $start = $half + 1;
            } else {
                return $arr[$half][1];
            }
        }
    }
    return 'undef';
}

function getSafeKey($k)
{
    $kk = strip_tags($k);
    $kk = htmlspecialchars($kk);
    $kk = mysql_escape_string($kk);
    return $kk;
}

echo '<!DOCTYPE html><html><head><meta charset="utf-8">
<title>Тестовое задание Бинарный поиск</title></head><body>';

if (isset($_POST['key'])) {
    $file = 'date.txt';
    $find_key = getSafeKey($_POST['key']);
    echo 'Результат: ' . bSearch($file, $find_key) . '<br>';
}

echo '<form action="index.php" method="post">
    <h2>Введите искомый ключ:</h2>
    <p><input type="text" name="key" placeholder="ключ1"/></p>
    <p><input type="submit" /></p>
    </form>';

echo '</body></html>';