<?php
/**
 * $Author$
 * $Revision$
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU Public License
 **/


function GetUrl($host, $url, $headers=false) {

//    return 'nothing yet';

    if ( ($fp = fsockopen($host, 80, $errno, $errstr, 10)) === false) return false;

    $in  = "GET $url HTTP/1.1\r\n";
    $in .= "Host: $host\r\n";
    $in .= 'User-Agent: '.$_SERVER['HTTP_USER_AGENT']."\r\n";
    $in .= 'Accept: '.$_SERVER['HTTP_ACCEPT']."\r\n";
    $in .= 'Accept-Language: '.$_SERVER['HTTP_ACCEPT_LANGUAGE']."\r\n";
    $in .= 'Accept-Charset: '.$_SERVER['HTTP_ACCEPT_CHARSET']."\r\n";
    $in .= "Accept-Encoding: chunked\r\n";
    if ($headers) $in .= $headers;
    $in .= "Connection: close\r\n\r\n";

    if (fwrite($fp, $in) === false) return false;

    $h=$b=$d='';
    while (!feof($fp)) {
        $d = fgets($fp, 128);
        if (stripos($h,"\r\n\r\n") === false)
            $h .= $d;
        else
            $b .= $d;
    }
    fclose($fp);
    if ( trim($b) == '') $b = false;
    return $b;
}

function GetSSlistHumainLike($SS,$pc) {
    list($x,$y) = map::ss2xy($SS); // Warn: x/y s'inverse ;)
    $lst = array();

    $miny = max($y-$pc, 0);   // 0
    $maxy = min($y+$pc, 99);  // 99

    $minx = max($x-$pc, 1);   // 1
    $maxx = min($x+$pc, 100); // 100 (ie. 0)

    $iy=$miny;
    $ix = ($iy%2)==0 ? $minx:$maxx;

    while ($iy<=$maxy) {

        $Ø  = ($iy%2)==0 ? 'return $ix<=$maxx;': 'return $ix>=$minx;';
        $Ð  = ($iy%2)==0 ? '$ix++;': '$ix--;';
        $Ð2 = ($iy%2)==0 ? '$ix--;': '$ix++;';

        while (eval($Ø)) {
            if ($iy < 0 || $iy > 99)
                continue;
            if ($ix < 1 || $ix > 100)
                continue;
            if ($ix == 100) {
                $iy2++;
                $ix2 = 0;
            } else {
                $ix2 = $ix;
                $iy2 = $iy;
            }

            $lst[] = ($iy2*100)+$ix2;
            eval($Ð);
        }
        eval($Ð2);
        $iy++;
    }

    return $lst;
}
