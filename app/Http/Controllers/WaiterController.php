<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Paybinding;
use App\Models\Payment;
use App\Models\Sessiondish;
use App\Models\Xodim;
use App\Models\Xodimrole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WaiterController extends Controller
{
    public function waiters(){
        $sn=now('Asia/Tashkent');

        $dan=date('Y-m-d', strtotime("-1 day", strtotime($sn)));
        $gacha=$dan;
        $dan=date($dan);
        $gacha=date($gacha);
        $gdan=$dan;
        $ggacha=$gacha;
        $gacha=date('Y-m-d', strtotime("+1 day", strtotime($gacha)));

        if(date('Y-m-d', strtotime("+1 day", strtotime($dan)))!=$gacha){
            $sanasi=" $gdan дан $ggacha гача";
        }else{
            $sanasi="$gdan";
        }
        $dan=date('Y-m-d H:i:s', strtotime("+3 hour", strtotime($dan)));
        $gacha=date('Y-m-d H:i:s', strtotime("+3 hour", strtotime($gacha)));
        $start_time = microtime(true);
        $sql="SELECT PAYMENTS.PAYLINETYPE, PAYBINDINGS.DISTRDISCOUNTS,MENUITEMS.NAME,SESSIONDISHES.QUANTITY,SESSIONDISHES.CLOSEDPAYSUM,SESSIONDISHES.VISIT as VISIT_ID,ORDERS.MAINWAITER AS WAITER_ID, SESSIONDISHES.UNI,SESSIONDISHES.CREATIONDATETIME  FROM SESSIONDISHES
INNER JOIN PAYBINDINGS ON SESSIONDISHES.VISIT = PAYBINDINGS.VISIT AND SESSIONDISHES.UNI = PAYBINDINGS.DISHUNI
INNER JOIN PAYMENTS ON SESSIONDISHES.VISIT=PAYMENTS.VISIT
INNER JOIN MENUITEMS ON SESSIONDISHES.SIFR = MENUITEMS.SIFR
INNER JOIN ORDERS ON SESSIONDISHES.VISIT=ORDERS.VISIT
WHERE PAYBINDINGS.STATE = 6 AND PAYMENTS.STATE = 6 AND SESSIONDISHES.CREATIONDATETIME >='$dan' AND SESSIONDISHES.CREATIONDATETIME<='$gacha';;";
        $session=DB::select($sql);

        $sql2="SELECT EMPLOYEES.SIFR AS ID,
      EMPLOYEES.NAME

  FROM EMPLOYEES";
        $b=DB::select($sql2);
//        $z1=[];
//        foreach ($b as $zz){
//            $z1[$zz['ID']]['ID']=$zz['ID'];
//            $z1[$zz['ID']]['NAME']=$zz['NAME'];
//        }
//        $b=$z1;
        $waiters=[];
        foreach ($b as $bb) {
            $waiters[$bb->ID] = [];
            $waiters[$bb->ID]['ID'] = $bb->ID;
            $waiters[$bb->ID]['NAME'] = $bb->NAME;
            $waiters[$bb->ID]['ISHLAGAN']=[];
            $waiters[$bb->ID]['FOYDA']=0;
            $waiters[$bb->ID]['USLUG']=0;
            $waiters[$bb->ID]['ISHLAGANSONI']=0;
        }
        foreach ($session as $s) {
            if (!isset($waiters[$s->WAITER_ID])) {
                continue;
            } else {
                $t = [];
                $t['DATA'] = $s;
                $t['TURI'] = $s->PAYLINETYPE ?? "Bekor qilingan";
                $uslugi = $s->DISTRDISCOUNTS ?? 0;
                $waiters[$s->WAITER_ID]['USLUG'] += $uslugi;
                $t['USLUG'] = $uslugi;

                $t['NAME'] =$s->NAME ?? "error";
                if ($s->CLOSEDPAYSUM > 0) {
                    $t['PULI'] = $s->CLOSEDPAYSUM/100-$uslugi;
                    $waiters[$s->WAITER_ID]['FOYDA'] += $t['PULI'];
                    $t['SONI'] = $s->QUANTITY;
                } else {

                    $t['PULI'] = 0;
                    $t['SONI'] = 0;
                }
                $waiters[$s->WAITER_ID]['ISHLAGANSONI'] +=$t['SONI'] ;
                $waiters[$s->WAITER_ID]['ISHLAGAN'][] = $t;
            }
        }

        $w_array=(object)$waiters;

        usort($waiters, function ($a, $b) {
            return $a['FOYDA'] <= $b['FOYDA'];
        });
        //grafik uchun
        $dataPoints = [];
        foreach ($waiters as $k => $mt) {
            if ($k == 15) {
                break;
            }
            $dataPoints[] = array("label" => $mt["NAME"], "y" => $mt["FOYDA"]);
        };
        $end_time = microtime(true);
        $execution_time = ($end_time - $start_time);

        return view('waiters',[
            'waiters'=>$waiters,
            'w_array'=>$w_array,
            'dataPoints'=>$dataPoints,
            'vaqt'=>$execution_time,
            'sanasi'=>$sanasi,
            'dan'=>$gdan,
            'gacha'=>$ggacha,

        ]);
    }
    public function waiters_post(Request $request){
        $dan=$request->dan;
        $gacha=$request->gacha;
        $dan=date($dan);
        $gacha=date($gacha);
        $gdan=$dan;
        $ggacha=$gacha;
        $gacha=date('Y-m-d', strtotime("+1 day", strtotime($gacha)));

        if(date('Y-m-d', strtotime("+1 day", strtotime($dan)))!=$gacha){
            $sanasi=" $gdan дан $ggacha гача";
        }else{
            $sanasi="$gdan";
        }
        $dan=date('Y-m-d H:i:s', strtotime("+3 hour", strtotime($dan)));
        $gacha=date('Y-m-d H:i:s', strtotime("+3 hour", strtotime($gacha)));
        $start_time = microtime(true);
        $sql="SELECT PAYMENTS.PAYLINETYPE, PAYBINDINGS.DISTRDISCOUNTS,MENUITEMS.NAME,SESSIONDISHES.QUANTITY,SESSIONDISHES.CLOSEDPAYSUM,SESSIONDISHES.VISIT as VISIT_ID,ORDERS.MAINWAITER AS WAITER_ID, SESSIONDISHES.UNI,SESSIONDISHES.CREATIONDATETIME  FROM SESSIONDISHES
INNER JOIN PAYBINDINGS ON SESSIONDISHES.VISIT = PAYBINDINGS.VISIT AND SESSIONDISHES.UNI = PAYBINDINGS.DISHUNI
INNER JOIN PAYMENTS ON SESSIONDISHES.VISIT=PAYMENTS.VISIT
INNER JOIN MENUITEMS ON SESSIONDISHES.SIFR = MENUITEMS.SIFR
INNER JOIN ORDERS ON SESSIONDISHES.VISIT=ORDERS.VISIT
WHERE PAYBINDINGS.STATE = 6 AND PAYMENTS.STATE = 6 AND SESSIONDISHES.CREATIONDATETIME >='$dan' AND SESSIONDISHES.CREATIONDATETIME<='$gacha';";
        $session=DB::select($sql);

        $sql2="SELECT EMPLOYEES.SIFR AS ID,
      EMPLOYEES.NAME

  FROM EMPLOYEES";
        $b=DB::select($sql2);
//        $z1=[];
//        foreach ($b as $zz){
//            $z1[$zz['ID']]['ID']=$zz['ID'];
//            $z1[$zz['ID']]['NAME']=$zz['NAME'];
//        }
//        $b=$z1;
        $waiters=[];
        foreach ($b as $bb) {
            $waiters[$bb->ID] = [];
            $waiters[$bb->ID]['ID'] = $bb->ID;
            $waiters[$bb->ID]['NAME'] = $bb->NAME;
            $waiters[$bb->ID]['ISHLAGAN']=[];
            $waiters[$bb->ID]['FOYDA']=0;
            $waiters[$bb->ID]['USLUG']=0;
            $waiters[$bb->ID]['ISHLAGANSONI']=0;
        }
        foreach ($session as $s) {
            if (!isset($waiters[$s->WAITER_ID])) {
                continue;
            } else {
                $t = [];
                $t['DATA'] = $s;
                $t['TURI'] = $s->PAYLINETYPE ?? "Bekor qilingan";
                $uslugi = $s->DISTRDISCOUNTS ?? 0;
                $waiters[$s->WAITER_ID]['USLUG'] += $uslugi;
                $t['USLUG'] = $uslugi;

                $t['NAME'] =$s->NAME ?? "error";
                if ($s->CLOSEDPAYSUM > 0) {
                    $t['PULI'] = $s->CLOSEDPAYSUM/100-$uslugi;
                    $waiters[$s->WAITER_ID]['FOYDA'] += $t['PULI'];
                    $t['SONI'] = $s->QUANTITY;
                } else {

                    $t['PULI'] = 0;
                    $t['SONI'] = 0;
                }
                $waiters[$s->WAITER_ID]['ISHLAGANSONI'] +=$t['SONI'] ;
                $waiters[$s->WAITER_ID]['ISHLAGAN'][] = $t;
            }
        }

        $w_array=(object)$waiters;

        usort($waiters, function ($a, $b) {
            return $a['FOYDA'] <= $b['FOYDA'];
        });
        //grafik uchun
        $dataPoints = [];
        foreach ($waiters as $k => $mt) {
            if ($k == 15) {
                break;
            }
            $dataPoints[] = array("label" => $mt["NAME"], "y" => $mt["FOYDA"]);
        };
        $end_time = microtime(true);
        $execution_time = ($end_time - $start_time);

        return view('waiters',[
            'waiters'=>$waiters,
            'w_array'=>$w_array,
            'dataPoints'=>$dataPoints,
            'vaqt'=>$execution_time,
            'sanasi'=>$sanasi,
            'dan'=>$gdan,
            'gacha'=>$ggacha,

        ]);
    }
}
