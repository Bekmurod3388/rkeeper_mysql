<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gavhar </title>
    <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet">
    <script>
        window.onload = function () {

            var chart = new CanvasJS.Chart("chartContainer", {
                animationEnabled: true,
                theme: "light1", // "light1", "light2", "dark1", "dark2"
                title: {
                    text: "15 та Энг кўп фойда келтирган афицантлар"
                },
                axisY: {
                    title: "Буюртма сони бўйича"

                },
                data: [{
                    type: "column",
                    dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
                }]
            });
            chart.render();

        }
    </script>
</head>
<body style="background: #d9edf8">

<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/home') }}">
            Гавҳар Миллий Таомлари
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent" style="padding-left: 30%">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav me-auto">
                <li class=" nav-item"><a href="{{route('waiters')}}" class="nav-link "> Афитсантлар статистикаси</a>
                </li>
                <li class=" nav-item"><a href="{{route('home')}}" class="nav-link  "> Таомлар статистикаси</a></li>
                <li class=" nav-item"><a href="{{route('savdo')}}" class="nav-link  "> Савдо статистикаси</a></li>
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ms-auto">
                <!-- Authentication Links -->
                @guest
                    @if (Route::has('login'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Кириш</a>
                        </li>
                    @endif

                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                    @endif
                @else
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                           data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }}
                        </a>

                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
<div class="card  m-2   w-100 ">
    {{--        <div class="card-header" style="background: #037a97; border-color:  #037a97; "></div>--}}
    <div class="card-body">
        <div class=" w-100 d-flex justify-content-center align-items-center " style="margin-bottom: 0px;">
            <form action="{{ route('waiters_post') }}" method="post" class="row" style="height:30px; ">
                @csrf
                {{--                <p>{{$vaqt}}</p>--}}

                <div class="col d-flex justify-content-start align-items-center ">
                    <input name="dan" style="width: 140px; height: 40px;" value="{{$dan}}" type="date" required
                           class="form-control ml-2 " id="staticEmail2">
                    <p style="margin-left: 3px; margin-right: 3px; margin-top: auto; margin-bottom:auto;">Санасидан</p>
                    <input name="gacha" style="width: 140px; height: 40px;" value="{{$gacha}}" type="date" required
                           class="form-control" id="inputPassword2">
                    <p style="margin-left: 3px; margin-right: 3px; margin-top: auto; margin-bottom:auto;">Санасигача</p>
                    <button style="width: 140px; height: 40px;"
                            type="submit" class="btn btn-primary ">Хисобот Куриш
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div class="card-footer" style="background: #037a97; border-color:  #037a97;  "></div>

</div>
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><span id="xodim_nomi"> xodim</span> - {{$sanasi}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="tozala()"
                        aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table style="font-size: 14px;"
                       class="table table-striped table-primary table-bordered border-2 text-center" id="modal_table">
                    <tr>
                        <td></td>
                        <td>#</td>
                        <td>Nomi</td>
                        <td>Sotilgan</td>
                        <td>Narxi</td>
                        <td>To'lov turi</td>
                    </tr>
                </table>
                <div class="w-100">
                    <div class="">
                    <p style="font-weight: 900; color: #037a97"> Умумий Сумма:<span style="color: #008d61" id="summa_u"></span> </p>
                    <p style="font-weight: 900; color: #037a97">  Умумий Услуг: <span style="color: #008d61" id="uslug_u"></span> </p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" onclick="tozala()" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="d-flex justify-content-between p-2">
    <div class="card w-50">
        <div class="card-header" style="background: #037a97; border-color:  #037a97; height: 47px; ">
            <p style="color: #efeeee; font-size: 16px; font-weight: 600;">Афицантлар <span> {{$sanasi}}</span></p>
        </div>

        <div class="card-body">
            <table class="table table-striped table-bordered border-1">
                <tr style="font-size: 14px;">
                    <th style="width: 80px;"></th>
                    <th>#</th>
                    <th>Исми</th>
                    <th>Сони</th>
                    <th>Сумма</th>
                    <th>Услуг</th>
                    <th>Сумма+Услуг</th>
                </tr>
                <?php $usm=0;$uug=0; ?>
                @foreach($waiters as $w)
                    @if($w['ISHLAGANSONI']==0) @continue @endif
                    <tr>
                        <td>
                            <button style="background: #008d61; color: #fffefe;font-weight: 500;
                       border: #008d61; border-radius: 6px; width: 70px;" type="button" onclick="zakazlar({{$w['ID']}})"
                                    data-bs-toggle="modal" data-bs-target="#exampleModal">
                                Кўриш
                            </button>
                        </td>
                        <td>{{$loop->index+1}}</td>
                        <td>
                            {{$w['NAME']}}

                        </td>
                        <td>   {{$w['ISHLAGANSONI']}}  </td>
                        <td style="font-size: 15px; font-weight: 900; width: 150px; ">
                        {{number_format($w['FOYDA'], 0, ',', ' ')}}
                        @php

$usm+=$w['FOYDA'];
$uug+=$w['USLUG'];


                        @endphp
                        <td style="font-size: 15px; font-weight: 900; width: 150px; ">{{number_format($w['USLUG'], 0, ',', ' ')}}

                        </td>
                        <td style="font-size: 15px; font-weight: 900;"> {{number_format($w['USLUG']+$w['FOYDA'], 0, ',', ' ')}}</td>
                    </tr>
                @endforeach
            </table>
            <div class="w-100">
                <div class="d-flex justify-content-between">
                    <p style="font-weight: 900; color: #037a97"> Умумий Сумма:<span style="color: #008d61" id=""> {{number_format($usm, 0, ',', ' ')}}</span> </p>
                    <p style="font-weight: 900; color: #037a97">  Умумий Услуг: <span style="color: #008d61" id="">{{number_format($uug, 0, ',', ' ')}}</span> </p>
                </div>
                <p style="font-weight: 900; color: #037a97">  Умумий Сумма + Услуг: <span style="color: #008d61" id="">{{number_format($uug+$usm, 0, ',', ' ')}}</span> </p>

            </div>
        </div>
        <div class="card-footer" style="background: #037a97">

        </div>
    </div>
    <div class="w-50 m-2 mt-0">
        <div class="card">
            <div class="card-header" style="background: #037a97; border-color:  #037a97; height: 47px; ">
                <p style="color: #efeeee; font-size: 16px; font-weight: 600;">График <span> {{$sanasi}}</span></p>
            </div>

            <div class="card-body">
                <div id="chartContainer" style="height: 370px; width: 100%;"></div>

            </div>
            <div class="card-footer" style="background: #037a97">
            </div>
        </div>

    </div>
</div>


<script>
    let waiters =@json($w_array);
    let table = document.getElementById('modal_table');

    function zakazlar(sifr) {
        tozala();

        let w;
        w = waiters[sifr];
        document.getElementById('xodim_nomi').innerHTML = w['NAME'];
        for (let i = 0; i < w['ISHLAGAN'].length; i++) {

            var row = table.insertRow();
            var cell1 = row.insertCell();
            var cell2 = row.insertCell();
            var cell3 = row.insertCell();
            var cell4 = row.insertCell();
            var cell41 = row.insertCell();
            var cell5 = row.insertCell();
            cell1.innerHTML = i + 1;
            cell2.innerHTML = w['ISHLAGAN'][i]['NAME'];
            let bq = w['ISHLAGAN'][i]['TURI'];
            let bb;
            if (bq == 0) {
                bb = "Naqd pul"
            } else {
                if (bq == 5) {
                    bb = "Plastik karta"
                } else {
                    bb = bq;
                }
            }
            let f = w['ISHLAGAN'][i]['DATA']['CLOSEDPAYSUM'] / 100 - w['ISHLAGAN'][i]['USLUG'];
            f = f.toLocaleString("fi-FI");
            let ff = w['ISHLAGAN'][i]['USLUG'];
            ff = Math.round(ff);
            ff = ff.toLocaleString("fi-FI");

            cell5.innerHTML = bb
            cell3.innerHTML = w['ISHLAGAN'][i]['DATA']['QUANTITY'];
            cell4.innerHTML = f + "";
            cell41.innerHTML = ff + "";
            if (w['ISHLAGAN'][i]['DATA']['CLOSEDPAYSUM'] == 0) {
                cell3.innerHTML = "-----";
                cell4.innerHTML = "-----";
                cell41.innerHTML = "-----";
                cell5.innerHTML = "Bekor qilingan";
            }
        }
        let ff;
        ff = Math.round(w['FOYDA']);
        ff = ff.toLocaleString("fi-FI");
        let fu;
        fu = Math.round(w['USLUG']);
        fu = fu.toLocaleString("fi-FI");
        document.getElementById('summa_u').innerHTML=ff+"";
        document.getElementById('uslug_u').innerHTML=fu+"";

    }

    function tozala() {

        table.innerHTML = "";
        var row = table.insertRow();
        var cell1 = row.insertCell();
        var cell2 = row.insertCell();
        var cell3 = row.insertCell();
        var cell4 = row.insertCell();
        var cell41 = row.insertCell();
        var cell5 = row.insertCell();
        cell1.innerHTML = "#";
        cell2.innerHTML = "Блюда";
        cell3.innerHTML = "Количество";
        cell4.innerHTML = "Сумма";
        cell41.innerHTML = "Услуг";
        cell5.innerHTML = "Тўлов тури";
    }
</script>
<script src="{{asset('canva/canvas.min.js')}}" type="text/javascript"></script>

<script src="{{asset('css/bootstrap.bundle.min.js')}}"></script>
</body>
</html>
