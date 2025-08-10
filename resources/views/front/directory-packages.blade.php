@extends('front.layout.master')

@section('main_content')

    <div class="page-top">
        <div class="container">
            <div class="breadcrumb-container">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('home')}}">Start</a></li>
                    <li class="breadcrumb-item active">Przegląd polecanych wycieczek</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="package pt_20">
    <div class="region-information">
        <div class="text">
            Pokaż ofertę dla:
            <script>
                function autoSubmit() {
                    document.getElementById("regionForm").submit();
                }
            </script>
            <form name="regionForm" id="regionForm" action="{{ route('packages') }}" method="get">
                <div class="select-form-div">
                    <select name="region_id" class="form-select-region-information" oninput="autoSubmit()">
                        <option selected value="16">Widok domyślny (Warszawa)</option>
                        @foreach($regions as $region)
                            <option value="{{ $region->id }}" @if($region_id == $region->id) selected @endif>{{ $region->name }} i okolice</option>
                        @endforeach
                    </select>
                </div>
            </form>

            <div class="icon"><i class="fas fa-info-circle"></i>
                <div class="explanation">
                    Prosimy o wybranie miasta opowiadającego miejscu wyjazdu lub miasta, które znajduje się najbliżej.
                </div>
            </div>
        </div>
    </div></div>
<div class="directory-buttons pt_10 pb_15">
    <div class="container">
        <!-- <div class="region-information">
            <div class="text">
                Pokaż ofertę dla:
                <script>
                    function autoSubmit() {
                        document.getElementById("regionForm").submit();
                    }
                </script>
                <form name="regionForm" id="regionForm" action="{{ route('packages') }}" method="get">
                    <div class="select-form-div">
                        <select name="region_id" class="form-select-region-information" oninput="autoSubmit()">
                            <option selected value="16">Widok domyślny (Warszawa)</option>
                            @foreach($regions as $region)
                                <option value="{{ $region->id }}" @if($region_id == $region->id) selected @endif>{{ $region->name }} i okolice</option>
                            @endforeach
                        </select>
                    </div>
                </form>

                <div class="icon"><i class="fas fa-info-circle"></i>
                    <div class="explanation">
                        Prosimy o wybranie miasta opowiadającego miejscu wyjazdu lub miasta, które znajduje się najbliżej.
                    </div>
                </div>
            </div>
        </div>

        <div class="directory-bar">
            <div class="region-bar">
                <div class="box-region">
                <div class="text">
                    <div class="h2 directory-navigation">Pokaż ofertę dla regionu:</div>
                    <script>
                        function autoSubmit() {
                            document.getElementById("regionForm").submit();
                        }
                    </script>
                    <div class="destination">
                        <form class="destination_search" action="{{ route('packages') }}" method="get" style="min-width: 80% !important;">
                            <div class="layout">
                                <div class="mobile-destination-from">
                                    <div class="destination_from">
                                        <div class="destination_from_select_option">
                                            <select name="region_id" class="destination_from_select_form" required>
                                                <option class="where_from" value="" disabled selected>Skąd? *</option>
                                                @foreach($regions as $region)
                                                    <option value="{{ $region->id }}"
                                                            @if($region_id == $region->id) selected @endif>
                                                        {{ $region->name }} i okolice
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="destination_from_search">
                                        </div>
                                    </div>
                                    <div class="icon"><i class="fas fa-info-circle"></i>
                                        <div class="explanation">
                                            Prosimy o wybranie miasta opowiadającego miejscu wyjazdu lub miasta, które znajduje się najbliżej.
                                        </div>
                                    </div></div>
                                <button class="destination_search_button" type="submit">Szukaj</button>
                            </div>
                        </form>
                    </div></div> -->
                    <div class="h2 directory-navigation">Przejdź do pełnej oferty wycieczek o długości...</div>
                    <div class="length-buttons">
                        <a href="{{ url('/packages?region_id=' . (request()->region_id ?? request()->cookie('region_id', 16)) . '&length_id=1') }}"><button>1 dzień</button></a>
                        <a href="{{ url('/packages?region_id=' . (request()->region_id ?? request()->cookie('region_id', 16)) . '&length_id=2') }}"><button>2 dni</button></a>
                        <a href="{{ url('/packages?region_id=' . (request()->region_id ?? request()->cookie('region_id', 16)) . '&length_id=3') }}"><button>3 dni</button></a>
                        <a href="{{ url('/packages?region_id=' . (request()->region_id ?? request()->cookie('region_id', 16)) . '&length_id=4') }}"><button>4 dni</button></a>
                        <a href="{{ url('/packages?region_id=' . (request()->region_id ?? request()->cookie('region_id', 16)) . '&length_id=5') }}"><button>5 dni</button></a>
                        <a href="{{ url('/packages?region_id=' . (request()->region_id ?? request()->cookie('region_id', 16)) . '&length_id=6') }}"><button>6 i więcej dni</button></a>
                    </div>
            </div>
    </div></div>
</div>
    <div class="directory-buttons days">
        <div class="container pt_25 pb_15">
            <div class="package-directory-box">
                <div class="top-text">
                    <div class="h2">Wycieczki 1-dniowe</div>
                </div>
            <div class="layout">
            @foreach($random_one_day as $item)
            <div class="package-preview">
                <div class="photo" style="background-image:url({{ asset('storage/' . ($item->featured_image ?? '')) }})"></div>
                <div class="text-window">
                    <div class="title"><a href="{{ route('package',$item->slug) }}">{{ $item->name }}</a></div>
                    <div class="price">
                        @if($item->price && $item->price !== "0")
                            od <b>{{ $item->price }} {{ $item->currency_symbol ?? 'PLN' }}</b> /os.
                        @else
                            <b>cena do ustalenia</b>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
                <a class="check-all" href="{{ url('/packages?region_id=' . (request()->region_id ?? request()->cookie('region_id', 16)) . '&length_id=1') }}">Zobacz wszystkie wycieczki 1-dniowe</a>
            </div>
        </div>
        </div>
        <div class="container pt_15 pb_15">
            <div class="package-directory-box">
            <div class="top-text">
                <div class="h2">Wycieczki 2-dniowe</div>
            </div>
            <div class="layout">
                @foreach($random_two_day as $item)
                    <div class="package-preview">
                        <div class="photo" style="background-image:url({{ asset('storage/' . ($item->featured_image ?? '')) }})"></div>
                        <div class="text-window">
                            <div class="title"><a href="{{ route('package',$item->slug) }}">{{ $item->name }}</a></div>
                            <div class="price">
                                @if($item->price && $item->price !== "0")
                                    od <b>{{ $item->price }} {{ $item->currency_symbol ?? 'PLN' }}</b> /os.
                                @else
                                    <b>cena do ustalenia</b>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
                    <a class="check-all" href="{{ url('/packages?region_id=' . (request()->region_id ?? request()->cookie('region_id', 16)) . '&length_id=2') }}">Zobacz wszystkie wycieczki 2-dniowe</a>
            </div>
            </div>
        </div>
        <div class="container pt_15 pb_15">
            <div class="package-directory-box">
            <div class="top-text">
                <div class="h2">Wycieczki 3-dniowe</div>
            </div>
            <div class="layout">
                @foreach($random_three_day as $item)
                    <div class="package-preview">
                        <div class="photo" style="background-image:url({{ asset('storage/' . ($item->featured_image ?? '')) }})"></div>
                        <div class="text-window">
                            <div class="title"><a href="{{ route('package',$item->slug) }}">{{ $item->name }}</a></div>
                            <div class="price">
                                @if($item->price && $item->price !== "0")
                                    od <b>{{ $item->price }} {{ $item->currency_symbol ?? 'PLN' }}</b> /os.
                                @else
                                    <b>cena do ustalenia</b>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
                    <a class="check-all" href="{{ url('/packages?region_id=' . (request()->region_id ?? request()->cookie('region_id', 16)) . '&length_id=3') }}">Zobacz wszystkie wycieczki 3-dniowe</a>
            </div>
            </div>
        </div>
        <div class="container pt_15 pb_15">
            <div class="package-directory-box">
            <div class="top-text">
                <div class="h2">Wycieczki 4-dniowe</div>
            </div>
            <div class="layout">
                @foreach($random_four_day as $item)
                    <div class="package-preview">
                        <div class="photo" style="background-image:url({{ asset('storage/' . ($item->featured_image ?? '')) }})"></div>
                        <div class="text-window">
                            <div class="title"><a href="{{ route('package',$item->slug) }}">{{ $item->name }}</a></div>
                            <div class="price">
                                @if($item->price && $item->price !== "0")
                                    od <b>{{ $item->price }} {{ $item->currency_symbol ?? 'PLN' }}</b> /os.
                                @else
                                    <b>cena do ustalenia</b>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
                    <a class="check-all" href="{{ url('/packages?region_id=' . (request()->region_id ?? request()->cookie('region_id', 16)) . '&length_id=4') }}">Zobacz wszystkie wycieczki 4-dniowe</a>
            </div>
            </div>
    </div>
        <div class="container pt_15 pb_15">
            <div class="package-directory-box">
            <div class="top-text">
                <div class="h2">Wycieczki 5-dniowe</div>
            </div>
            <div class="layout">
                @foreach($random_five_day as $item)
                    <div class="package-preview">
                        <div class="photo" style="background-image:url({{ asset('storage/' . ($item->featured_image ?? '')) }})"></div>
                        <div class="text-window">
                            <div class="title"><a href="{{ route('package',$item->slug) }}">{{ $item->name }}</a></div>
                            <div class="price">
                                @if($item->price && $item->price !== "0")
                                    od <b>{{ $item->price }} {{ $item->currency_symbol ?? 'PLN' }}</b> /os.
                                @else
                                    <b>cena do ustalenia</b>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
                    <a class="check-all" href="{{ url('/packages?region_id=' . (request()->region_id ?? request()->cookie('region_id', 16)) . '&length_id=5') }}">Zobacz wszystkie wycieczki 5-dniowe</a>
            </div>
            </div>
        </div>
        <div class="container pt_15 pb_70">
            <div class="top-text">
                <div class="h2">Wycieczki 6-dniowe i dłuższe</div>
            </div>
            <div class="layout">
                @foreach($random_six_day as $item)
                    <div class="package-preview">
                        <div class="photo" style="background-image:url({{ asset('storage/' . ($item->featured_image ?? '')) }})"></div>
                        <div class="text-window">
                            <div class="title"><a href="{{ route('package',$item->slug) }}">{{ $item->name }}</a></div>
                            <div class="price">
                                @if($item->price && $item->price !== "0")
                                    od <b>{{ $item->price }} {{ $item->currency_symbol ?? 'PLN' }}</b> /os.
                                @else
                                    <b>cena do ustalenia</b>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
                    <a class="check-all" href="{{ url('/packages?region_id=' . (request()->region_id ?? request()->cookie('region_id', 16)) . '&length_id=6') }}">Zobacz wszystkie wycieczki 6-dniowe i dłuższe</a>
            </div>
        </div>
    </div>
@endsection
