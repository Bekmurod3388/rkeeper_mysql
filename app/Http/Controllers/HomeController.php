<?php

namespace App\Http\Controllers;

use App\Models\Classificator;
use App\Models\Coteglist;
use App\Models\Dishgroup;
use App\Models\Menu;
use App\Models\Paybinding;
use App\Models\Sessiondish;
use App\Models\Xodim;
use App\Models\Xodimrole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
//    public function __construct()
//    {
//        $this->middleware('auth');
//    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $sn='02-07-2022';

        $dan=date('Y-m-d', strtotime("-1 day", strtotime($sn)));
        $gacha=date('Y-m-d', strtotime("+0 day", strtotime($sn)));
        $gachaa = $dan;
//        $gacha = date('Y-m-d', strtotime("+1 day", strtotime($gachaa)));
        $sql = "SELECT PAYMENTS.PAYLINETYPE, SESSIONDISHES.QUANTITY, SESSIONDISHES.PRICE,SESSIONDISHES.CLOSEDPAYSUM,
            SESSIONDISHES.SIFR,   MENUITEMS.CODE,   MENUITEMS.NAME,   CLASSIFICATORGROUPS.IDENT,	   CLASSIFICATORGROUPS.NAME AS CLASS
	        FROM SESSIONDISHES
	        INNER JOIN PAYMENTS ON SESSIONDISHES.VISIT=PAYMENTS.VISIT
            INNER JOIN MENUITEMS ON SESSIONDISHES.SIFR = MENUITEMS.SIFR
            INNER JOIN DISHGROUPS ON DISHGROUPS.CHILD = MENUITEMS.SIFR
            INNER JOIN CLASSIFICATORGROUPS ON CLASSIFICATORGROUPS.IDENT = DISHGROUPS.PARENT
            WHERE SESSIONDISHES.CLOSEDPAYSUM > 0 AND CLASSIFICATORGROUPS.PARENT = 2560 AND CLASSIFICATORGROUPS.STATUS > 0 ".
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
            $classificator[$v['IDENT']]['MONEY'] = 0;
            $classificator[$v['IDENT']]['CARD'] = 0;
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
            $men[$value['SIFR']]['MONEY'] = 0;
            $men[$value['SIFR']]['CARD'] = 0;
            $classificator[$value['IDENT']]['COUNT'] += $value['QUANTITY'];
            $classificator[$value['IDENT']]['PAYSUM'] += $value['CLOSEDPAYSUM'];
            if ($value['PAYLINETYPE'] == 0)
                $classificator[$value['IDENT']]['MONEY'] +=  $value['CLOSEDPAYSUM'];
            if ($value['PAYLINETYPE'] == 5)
                $classificator[$value['IDENT']]['CARD'] +=  $value['CLOSEDPAYSUM'];
        }
        foreach ($session as $value) {
            $men[$value['SIFR']]['COUNT'] += $value['QUANTITY'];
            $men[$value['SIFR']]['PAYSUM'] += round($value['CLOSEDPAYSUM'] / 100, 2);
            if ($value['PAYLINETYPE'] == 0)
                $men[$value['SIFR']]['MONEY'] += round($value['CLOSEDPAYSUM'] / 100, 2);
            if ($value['PAYLINETYPE'] == 5)
                $men[$value['SIFR']]['CARD'] += round($value['CLOSEDPAYSUM'] / 100, 2);
        }
        usort($classificator, function ($a, $b) {
            return $a['PAYSUM'] <= $b['PAYSUM'];
        });
        $dataPoints = [];
        foreach ($classificator as $k => $mt) {
            $dataPoints[] = array("label" => $mt["NAME"], "y" => round($mt["PAYSUM"] / 100,2));
        };
        usort($men, function ($a, $b) {
            return $a['NAME'] >= $b['NAME'];
        });
//        dd($dataPoints);
        return view('home', [
            'classificator' => $classificator,
            'menu' => $men,
            'dataPoints' => $dataPoints,
            'dan' => $dan,
            'gacha' => $gachaa,
        ]);
    }

    public function index_post(Request $request)
    {
        $dann = $request->dan;
        $gachaa = $request->gacha;
        $gacha = date('Y-m-d', strtotime("+ 27 hour", strtotime($gachaa)));
        $dan = date('Y-m-d', strtotime($dann));

        $sql = "SELECT PAYMENTS.PAYLINETYPE, SESSIONDISHES.QUANTITY, SESSIONDISHES.PRICE,SESSIONDISHES.CLOSEDPAYSUM,
       SESSIONDISHES.SIFR,    MENUITEMS.CODE,    MENUITEMS.NAME,   CLASSIFICATORGROUPS.IDENT,	   CLASSIFICATORGROUPS.NAME AS CLASS
	   FROM SESSIONDISHES
	INNER JOIN PAYMENTS ON SESSIONDISHES.VISIT=PAYMENTS.VISIT
      INNER JOIN MENUITEMS ON SESSIONDISHES.SIFR = MENUITEMS.SIFR
      INNER JOIN DISHGROUPS ON DISHGROUPS.CHILD = MENUITEMS.SIFR
      INNER JOIN CLASSIFICATORGROUPS ON CLASSIFICATORGROUPS.IDENT = DISHGROUPS.PARENT
      WHERE SESSIONDISHES.CLOSEDPAYSUM > 0 AND CLASSIFICATORGROUPS.PARENT = 2560 AND CLASSIFICATORGROUPS.STATUS > 0 ".
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
            $classificator[$v['IDENT']]['MONEY'] = 0;
            $classificator[$v['IDENT']]['CARD'] = 0;
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
            $men[$value['SIFR']]['MONEY'] = 0;
            $men[$value['SIFR']]['CARD'] = 0;
            $classificator[$value['IDENT']]['COUNT'] += $value['QUANTITY'];
            $classificator[$value['IDENT']]['PAYSUM'] += $value['CLOSEDPAYSUM'];
            if ($value['PAYLINETYPE'] == 0)
                $classificator[$value['IDENT']]['MONEY'] +=  $value['CLOSEDPAYSUM'];
            if ($value['PAYLINETYPE'] == 5)
                $classificator[$value['IDENT']]['CARD'] +=  $value['CLOSEDPAYSUM'];
        }
        foreach ($session as $value) {
            $men[$value['SIFR']]['COUNT'] += $value['QUANTITY'];
            $men[$value['SIFR']]['PAYSUM'] += round($value['CLOSEDPAYSUM'] / 100, 2);
            if ($value['PAYLINETYPE'] == 0)
                $men[$value['SIFR']]['MONEY'] += round($value['CLOSEDPAYSUM'] / 100, 2);
            if ($value['PAYLINETYPE'] == 5)
                $men[$value['SIFR']]['CARD'] += round($value['CLOSEDPAYSUM'] / 100, 2);
        }
        usort($classificator, function ($a, $b) {
            return $a['PAYSUM'] <= $b['PAYSUM'];
        });
        usort($men, function ($a, $b) {
            return $a['NAME'] >= $b['NAME'];
        });
        $dataPoints = [];
        foreach ($classificator as $k => $mt) {
            $dataPoints[] = array("label" => $mt["NAME"], "y" => round($mt["PAYSUM"]/100, 2));
        };
        return view('home', [
            'classificator' => $classificator,
            'menu' => $men,
            'dataPoints' => $dataPoints,
            'dan' => $dan,
            'gacha' => $gachaa,
        ]);
    }
}
