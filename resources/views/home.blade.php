<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gavhar</title>
    <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet">
    <script>
        window.onload = function () {

            var chart = new CanvasJS.Chart("chartContainer", {
                animationEnabled: true,
                theme: "light1", // "light1", "light2", "dark1", "dark2"
                title: {
                    text: "15 та Энг кўп фойда кўрган категориялар"
                },
                axisY: {
                    title: "Категориялар суммаси бўйича"
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
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent" style="padding-left: 30%">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav me-auto">
                <li class=" nav-item">  <a href="{{route('waiters')}}" class="nav-link "> Афитсантлар статистикаси</a></li>
                <li class=" nav-item">  <a href="{{route('home')}}" class="nav-link  "> Таомлар статистикаси</a></li>
                <li class=" nav-item">  <a href="{{route('savdo')}}" class="nav-link  "> Савдо статистикаси</a></li>
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
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
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
<div class="card  m-2 w-100 " >
    {{--        <div class="card-header" style="background: #037a97; border-color:  #037a97; "></div>--}}
    <div class="card-body">
        <div class=" w-100 d-flex justify-content-center align-items-center " style="margin-bottom: 0px;">
            <form action="{{ route('search') }}" method="post" class="row" style="height:30px; ">
                @csrf
                <div class="col d-flex justify-content-start align-items-center ">
                    <input name="dan" style="width: 140px; height: 40px;" type="date" required
                           class="form-control ml-2 " id="staticEmail2">
                    <p style="margin-left: 3px; margin-right: 3px; margin-top: auto; margin-bottom:auto;">Санасидан</p>
                    <input name="gacha" style="width: 140px; height: 40px;" type="date" required class="form-control"
                           id="inputPassword2">
                    <p style="margin-left: 3px; margin-right: 3px; margin-top: auto; margin-bottom:auto;">
                        Санасигача</p>
                    <button style="width: 140px; height: 40px;" type="submit" class="btn btn-primary ">Хисобот Куриш
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
                <h5 class="modal-title" id="exampleModalLabel">Xodim nomi -00.00.2022</h5>
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
                    <p style="font-weight: 900; color: #037a97"> Умумий Сумма: <span style="color: #008d61" id="summa_u"></span> </p>
                    <p style="font-weight: 900; color: #037a97">  Умумий Накд: <span style="color: #008d61" id="money"></span> </p>
                    <p style="font-weight: 900; color: #037a97">  Умумий Пластик: <span style="color: #008d61" id="card"></span> </p>
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
            <p style="color: #efeeee; font-size: 16px; font-weight: 600;">Категориялар бўйича савдо <span> {{ $dan }} дан {{ $gacha }} гача</span>
            </p>
        </div>

        <div class="card-body">
            <table class="table table-striped table-bordered border-1">
                <tr style="font-size: 14px;">
                    <td class="col-1"></td>
                    <th class="col-1">#</th>
                    <th class="col-2">Категория</th>
                    <th class="col-2">Сони</th>
                    <th class="col-3">Сумма</th>
                    <th class="col-3">Накд</th>
                    <th class="col-3">Пластик</th>
                </tr>
                @php
                    $money = 0;
                    $card = 0;
                    $pay = 0;
                @endphp
                @foreach($classificator as $key => $w)
                    @if($w['PAYSUM'] != 0)
                        <tr>
                            @php
                                $money += $w['MONEY'];
                                $card += $w['CARD'];
                                $pay += $w['PAYSUM'];
                            @endphp
                            <td>
                                <button
                                    style="background: #008d61; color: #fffefe;font-weight: 500; border: #008d61; border-radius: 6px; width: 70px;"
                                    type="button" onclick="zakazlar({{$key}})" data-bs-toggle="modal"
                                    data-bs-target="#exampleModal">
                                    Кўриш
                                </button>
                            </td>
                            <td>{{$loop->index+1}}</td>
                            <td>
                                {{$w['NAME']}}
                            </td>
                            <td>   {{ number_format($w['COUNT'], 2, ',', ' ') }}  </td>
                            <td style="font-size: 15px; font-weight: 900; width: 150px; ">{{ number_format($w['PAYSUM']/100, 2, ',', ' ') }} </td>
                            <td style="font-size: 15px; font-weight: 900; width: 150px; ">{{ number_format($w['MONEY']/100, 2, ',', ' ') }} </td>
                            <td style="font-size: 15px; font-weight: 900; width: 150px; ">{{ number_format($w['CARD']/100, 2, ',', ' ') }} </td>
                        </tr>
                    @endif
                @endforeach
            </table>
            <div class="w-100">
                <div class="d-flex justify-content-between">
                    <p style="font-weight: 900; color: #037a97"> Умумий Сумма:<span style="color: #008d61" id=""> {{number_format($pay/100, 0, ',', ' ')}}</span> </p>
                    <p style="font-weight: 900; color: #037a97"> Умумий Накд:<span style="color: #008d61" id=""> {{number_format($money/100, 0, ',', ' ')}}</span> </p>
                    <p style="font-weight: 900; color: #037a97"> Умумий Пластик:<span style="color: #008d61" id=""> {{number_format($card/100, 0, ',', ' ')}}</span> </p>
{{--                    <p style="font-weight: 900; color: #037a97">  Умумий Услуг: <span style="color: #008d61" id="">{{number_format($uug, 0, ',', ' ')}}</span> </p>--}}
                </div>
            </div>
        </div>
        <div class="card-footer" style="background: #037a97">

        </div>
    </div>
    <div class="w-50 m-2 mt-0">
        <div class="card">
            <div class="card-header" style="background: #037a97; border-color:  #037a97; height: 47px; ">
                <p style="color: #efeeee; font-size: 16px; font-weight: 600;">График <span>{{ $dan }} дан {{ $gacha }} гача</span></p>
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
    let menus =@json($menu);
    let cat = @json($classificator);
    let table = document.getElementById('modal_table');
    function zakazlar(sifr) {
        tozala();
        Object.values(menus).forEach(menus => {
            if (menus['CLASSIFICATOR'] == cat[sifr]['id'] && menus['PAYSUM'] != 0) {
                var row = table.insertRow();
                var cell1 = row.insertCell();
                var cell2 = row.insertCell();
                var cell3 = row.insertCell();
                var cell4 = row.insertCell();
                var cell5 = row.insertCell();
                var cell6 = row.insertCell();
                var cell7 = row.insertCell();
                var cell8 = row.insertCell();
                cell1.innerHTML = (menus['CODE'])
                cell2.innerHTML = (menus['NAME'])
                cell3.innerHTML = parseInt(menus['PRICE']).toLocaleString('fi-FI')
                cell4.innerHTML = menus['COUNT'].toFixed(2);
                cell5.innerHTML = (menus['COUNT'] * parseInt(menus['PRICE'])).toLocaleString('fi-FI')
                cell6.innerHTML = menus['PAYSUM'].toLocaleString('fi-FI')
                cell7.innerHTML = menus['MONEY'].toLocaleString('fi-FI')
                cell8.innerHTML = menus['CARD'].toLocaleString('fi-FI')
            }
        });
        let ff;
        ff = Math.round(cat[sifr]['PAYSUM']/100);
        ff = ff.toLocaleString("fi-FI");
        let fu;
        fu = Math.round(cat[sifr]['MONEY']/100);
        fu = fu.toLocaleString("fi-FI");
        let card;
        card = Math.round(cat[sifr]['CARD']/100);
        card = card.toLocaleString("fi-FI");
        document.getElementById('summa_u').innerHTML=ff;
        document.getElementById('money').innerHTML=fu;
        document.getElementById('card').innerHTML=card;

        document.getElementById('exampleModalLabel').innerHTML = cat[sifr]['NAME'];
    }

    function tozala() {
        table.innerHTML = "";
        var row = table.insertRow();
        var cell1 = row.insertCell();
        var cell2 = row.insertCell();
        var cell3 = row.insertCell();
        var cell4 = row.insertCell();
        var cell5 = row.insertCell();
        var cell6 = row.insertCell();
        var cell7 = row.insertCell();
        var cell8 = row.insertCell();
        cell1.innerHTML = "Код";
        cell2.innerHTML = "Блюда";
        cell3.innerHTML = "Цена";
        cell4.innerHTML = "Количество";
        cell5.innerHTML = "Сумма";
        cell6.innerHTML = "Сумма По Оплате";
        cell7.innerHTML = "Накд";
        cell8.innerHTML = "Пластик";
    }
</script>
<script src="{{asset('canva/canvas.min.js')}}" type="text/javascript"></script>

<script src="{{asset('css/bootstrap.bundle.min.js')}}"></script>

</body>
</html>
