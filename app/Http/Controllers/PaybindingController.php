<?php

namespace App\Http\Controllers;

use App\Models\Classificator;
use App\Models\Dishgroup;
use App\Models\Menu;
use Illuminate\Http\Request;
use App\Models\Paybinding;
use Illuminate\Support\Facades\DB;

class PaybindingController extends Controller
{
    public function savdo()
    {
        $sn=now('Asia/Tashkent');

        $dan=date('Y-m-d', strtotime("-1 day", strtotime($sn)));
        $gacha=date('Y-m-d', strtotime("+0 day", strtotime($sn)));
//        $gacha=$dan;
        $gachaa = $dan;
        $sql = "SELECT SESSIONDISHES.QUANTITY, SESSIONDISHES.PRICE, SESSIONDISHES.CREATIONDATETIME,
            PAYBINDINGS.PAYSUM,  PAYBINDINGS.COSTSUM,
            SESSIONDISHES.SIFR,    MENUITEMS.CODE,    MENUITEMS.NAME,   CLASSIFICATORGROUPS.IDENT,	   CLASSIFICATORGROUPS.NAME AS CLASS
	        FROM PAYBINDINGS
            INNER JOIN SESSIONDISHES ON PAYBINDINGS.DISHUNI = SESSIONDISHES.UNI AND PAYBINDINGS.VISIT = SESSIONDISHES.VISIT
            INNER JOIN MENUITEMS ON SESSIONDISHES.SIFR = MENUITEMS.SIFR
            INNER JOIN DISHGROUPS ON DISHGROUPS.CHILD = MENUITEMS.SIFR
            INNER JOIN CLASSIFICATORGROUPS ON CLASSIFICATORGROUPS.IDENT = DISHGROUPS.PARENT
            WHERE SESSIONDISHES.CLOSEDPAYSUM > 0 AND CLASSIFICATORGROUPS.PARENT = 2560 AND CLASSIFICATORGROUPS.STATUS > 0
             AND PAYBINDINGS.STATE = 6" .
            " AND SESSIONDISHES.CREATIONDATETIME >= '$dan 03:00' AND SESSIONDISHES.CREATIONDATETIME <= '$gacha 03:00'";
        $session = DB::select($sql);
        $session = json_decode(json_encode($session), true);
        $sql = "SELECT CLASSIFICATORGROUPS.IDENT, CLASSIFICATORGROUPS.NAME
                FROM CLASSIFICATORGROUPS
                WHERE CLASSIFICATORGROUPS.PARENT = 2560 AND CLASSIFICATORGROUPS.STATUS > 0;";
        $class = DB::select($sql);
        $class = json_decode(json_encode($class), true);
        foreach ($class as $v) {
            $classificator[$v['IDENT']] = [];
            $classificator[$v['IDENT']]['id'] = $v['IDENT'];
            $classificator[$v['IDENT']]['NAME'] = $v['NAME'];
            $classificator[$v['IDENT']]['COUNT'] = 0;
            $classificator[$v['IDENT']]['PAYSUM'] = 0;
            $classificator[$v['IDENT']]['COSTSUM'] = 0;
        }
        $men = [];
        foreach ($session as $value) {
            $men[$value['SIFR']]['id'] = $value['SIFR'];
            $men[$value['SIFR']]['NAME'] = $value['NAME'];
            $men[$value['SIFR']]['PRICE'] = $value['PRICE'];
            $men[$value['SIFR']]['CODE'] = $value['CODE'];
            $men[$value['SIFR']]['CLASSIFICATOR'] = $value['IDENT'];
            $men[$value['SIFR']]['COUNT'] = 0;
            $men[$value['SIFR']]['PAYSUM'] = 0;
            $men[$value['SIFR']]['COSTSUM'] = 0;
            $classificator[$value['IDENT']]['COUNT'] += $value['QUANTITY'];
            $classificator[$value['IDENT']]['PAYSUM'] += $value['PAYSUM'];
            $classificator[$value['IDENT']]['COSTSUM'] += $value['COSTSUM'];
        }
        $data = '';
        foreach ($session as $value) {
                $men[$value['SIFR']]['COUNT'] += $value['QUANTITY'];
                $men[$value['SIFR']]['PAYSUM'] += $value['PAYSUM'];
                $men[$value['SIFR']]['COSTSUM'] += $value['COSTSUM'];
        }
//        dd($data);
        usort($classificator, function ($a, $b) {
            return $a['PAYSUM'] - $a['COSTSUM'] <= $b['PAYSUM'] - $b['COSTSUM'];
        });
        $dataPoints = [];
        foreach ($classificator as $k => $mt) {
            $dataPoints[] = array("label" => $mt["NAME"], "y" => ($mt["PAYSUM"] - $mt["COSTSUM"]));
        };
        usort($men, function ($a, $b) {
            return $a['NAME'] >= $b['NAME'];
        });
        return view('savdo', [
            'classificator' => $classificator,
            'menu' => $men,
            'dataPoints' => $dataPoints,
            'dan' => $dan,
            'gacha' => $gachaa,
        ]);
    }

    public function savdo_post(Request $request)
    {
        $dann = $request->dan;
        $gachaa = $request->gacha;
        $gacha = date('Y-m-d', strtotime("+ 27 hour", strtotime($gachaa)));
        $dan = date('Y-m-d', strtotime($dann));

        $sql = "SELECT SESSIONDISHES.QUANTITY, SESSIONDISHES.PRICE, SESSIONDISHES.CREATIONDATETIME,
            PAYBINDINGS.PAYSUM,  PAYBINDINGS.COSTSUM,
            SESSIONDISHES.SIFR, MENUITEMS.CODE,     MENUITEMS.NAME,   CLASSIFICATORGROUPS.IDENT,	   CLASSIFICATORGROUPS.NAME AS CLASS
	        FROM PAYBINDINGS
            INNER JOIN SESSIONDISHES ON PAYBINDINGS.DISHUNI = SESSIONDISHES.UNI AND PAYBINDINGS.VISIT = SESSIONDISHES.VISIT
            INNER JOIN MENUITEMS ON SESSIONDISHES.SIFR = MENUITEMS.SIFR
            INNER JOIN DISHGROUPS ON DISHGROUPS.CHILD = MENUITEMS.SIFR
            INNER JOIN CLASSIFICATORGROUPS ON CLASSIFICATORGROUPS.IDENT = DISHGROUPS.PARENT
            WHERE SESSIONDISHES.CLOSEDPAYSUM > 0 AND CLASSIFICATORGROUPS.PARENT = 2560 AND CLASSIFICATORGROUPS.STATUS > 0
                AND PAYBINDINGS.STATE = 6" .
            " AND SESSIONDISHES.CREATIONDATETIME >= '$dan 03:00' AND SESSIONDISHES.CREATIONDATETIME <= '$gacha 03:00'";
        $session = DB::select($sql);
        $session = json_decode(json_encode($session), true);
        $sql = "SELECT CLASSIFICATORGROUPS.IDENT, CLASSIFICATORGROUPS.NAME
                FROM CLASSIFICATORGROUPS
                WHERE CLASSIFICATORGROUPS.PARENT = 2560 AND CLASSIFICATORGROUPS.STATUS > 0;";
        $class = DB::select($sql);
        $class = json_decode(json_encode($class), true);
        foreach ($class as $v) {
            $classificator[$v['IDENT']] = [];
            $classificator[$v['IDENT']]['id'] = $v['IDENT'];
            $classificator[$v['IDENT']]['NAME'] = $v['NAME'];
            $classificator[$v['IDENT']]['COUNT'] = 0;
            $classificator[$v['IDENT']]['PAYSUM'] = 0;
            $classificator[$v['IDENT']]['COSTSUM'] = 0;
        }
        $men = [];
        foreach ($session as $value) {
            $men[$value['SIFR']]['id'] = $value['SIFR'];
            $men[$value['SIFR']]['NAME'] = $value['NAME'];
            $men[$value['SIFR']]['PRICE'] = $value['PRICE'];
            $men[$value['SIFR']]['CODE'] = $value['CODE'];
            $men[$value['SIFR']]['CLASSIFICATOR'] = $value['IDENT'];
            $men[$value['SIFR']]['COUNT'] = 0;
            $men[$value['SIFR']]['PAYSUM'] = 0;
            $men[$value['SIFR']]['COSTSUM'] = 0;
            $classificator[$value['IDENT']]['COUNT'] += $value['QUANTITY'];
            $classificator[$value['IDENT']]['PAYSUM'] += $value['PAYSUM'];
            $classificator[$value['IDENT']]['COSTSUM'] += $value['COSTSUM'];
        }
        foreach ($session as $value) {
            $men[$value['SIFR']]['COUNT'] += $value['QUANTITY'];
            $men[$value['SIFR']]['PAYSUM'] += $value['PAYSUM'];
            $men[$value['SIFR']]['COSTSUM'] += $value['COSTSUM'];
        }
        usort($classificator, function ($a, $b) {
            return $a['PAYSUM'] - $a['COSTSUM'] <= $b['PAYSUM'] - $b['COSTSUM'];
        });
        usort($men, function ($a, $b) {
            return $a['NAME'] >= $b['NAME'];
        });
        $dataPoints = [];
        foreach ($classificator as $k => $mt) {
            $dataPoints[] = array("label" => $mt["NAME"], "y" => ($mt["PAYSUM"] - $mt["COSTSUM"]));
        };
        return view('savdo', [
            'classificator' => $classificator,
            'menu' => $men,
            'dataPoints' => $dataPoints,
            'dan' => $dan,
            'gacha' => $gachaa,
        ]);
    }
}
