<?php
function Date_DaysInMonth($m,$y) {
        $d_in_m = array(0,31,28,31,30,31,30,31,31,30,31,30,31);
        if (Date_LeapYear($y)) $d_in_m[2]=29;
        return $d_in_m[$m];
}
function Date_DaysInYear($y) {
        if (Date_LeapYear($y)) return 366;
        return 365;
}
function Date_DayOfYear($m,$d,$y) {
        $days = array( 0, 31, 59, 90,120,151,181,212,243,273,304,334,365);
        $ly=0;
        if ($m>2 && Date_LeapYear($y)) $ly=1;
        return ($days[$m-1]+$d+$ly);
}
function Date_LeapYear($y) {
        if(! ($y % 4 == 0) ) return 0;
        if(! ($y % 100 == 0)) return 1;
        if(! ($y % 400 == 0)) return 0;
        return 1;
}
function fecha_cobro()
{
    $time = time();
    $date = localtime($time,true); $date["tm_year"]+=1900; $date["tm_mon"]++;
    $d1=$d2=$date["tm_mday"]; $m1=$m2=$date["tm_mon"]; $y1=$y2=$date["tm_year"];

    $m2++; if($m2>12){ $m2=1; $y2++; }
    if($d2>Date_DaysInMonth($m2,$y2)){ $d2=Date_DaysInMonth($m2,$y2); }
    $d=0;
    if ($y2>$y1) {
      $d=Date_DaysInYear($y1) - Date_DayOfYear($m1,$d1,$y1);
      $d+=Date_DayOfYear($m2,$d2,$y2);
      for ($y=$y1+1; $y<$y2; $y++) {
        $d+= Date_DaysInYear($y);
      }
    } else {
      $d=Date_DayOfYear($m2,$d2,$y2) - Date_DayOfYear($m1,$d1,$y1);
    }

    return( ajustar_cobro($time + $d*24*3600) );
}

function fecha_alerta()
{
    $time = time();
    $date = localtime($time,true); $date["tm_year"]+=1900; $date["tm_mon"]++;
    $d1=$d2=$date["tm_mday"]; $m1=$m2=$date["tm_mon"]; $y1=$y2=$date["tm_year"];

    $m2++; if($m2>12){ $m2=1; $y2++; }
    $d2-=2; if($d2==0){ $m2--; if($m2==0){ $m2=12; $y2--; } $d2=Date_DaysInMonth($m2,$y2); }
    if($d2>Date_DaysInMonth($m2,$y2)){ $d2=Date_DaysInMonth($m2,$y2); }
    $d=0;
    if ($y2>$y1) {
      $d=Date_DaysInYear($y1) - Date_DayOfYear($m1,$d1,$y1);
      $d+=Date_DayOfYear($m2,$d2,$y2);
      for ($y=$y1+1; $y<$y2; $y++) {
        $d+= Date_DaysInYear($y);
      }
    } else {
      $d=Date_DayOfYear($m2,$d2,$y2) - Date_DayOfYear($m1,$d1,$y1);
    }

    return( ajustar_alerta($time + $d*24*3600) );
}
function fecha($time)
{
    $date = localtime($time,true); $date["tm_year"]+=1900; $date["tm_mon"]++;
    return(sprintf("%02d/%02d/%02d",$date["tm_mday"],$date["tm_mon"],substr($date["tm_year"],2,2)));
}
function ajustar_cobro($time)
{
    $date = localtime($time,true); $date["tm_year"]+=1900; $date["tm_mon"]++;
    if     ($date["tm_hour"]>=8  and $date["tm_hour"]<15) $date["tm_hour"] = 8;
    else if($date["tm_hour"]>=15 and $date["tm_hour"]<23) $date["tm_hour"] = 15;
    else                                                  $date["tm_hour"] = 23;
    $ret = mktime($date["tm_hour"],0,0,$date["tm_mon"],$date["tm_mday"],$date["tm_year"]);
    return ($ret);
}
function ajustar_alerta($time)
{
    $date = localtime($time,true); $date["tm_year"]+=1900; $date["tm_mon"]++;
    if     ($date["tm_hour"]>=22  or $date["tm_hour"]<8) $date["tm_hour"] = 8;
    $ret = mktime($date["tm_hour"],0,0,$date["tm_mon"],$date["tm_mday"],$date["tm_year"]);
    return ($ret);
}
function print_arreglo($vp_arreglo)
{
    $var="Array{\n";
    $cantidad_registros =sizeof($vp_arreglo);
    $level=0;
    $contador=0;
    while ($contador > $cantidad_registros)
    {
        $var.="[".$vp_arreglo[$contador][0]."]";
        $contador++;
    }
    $var.="}";
    return $var;
}
?>
