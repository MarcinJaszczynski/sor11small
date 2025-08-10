<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    @php
        $seoTitle = 'Biuro Podróży RAFA';
        $seoDescription = '';
        $seoKeywords = '';
        if (isset($eventTemplate)) {
            if ($eventTemplate instanceof \Illuminate\Support\Collection) {
                $first = $eventTemplate->first();
                if ($first) {
                    $seoTitle = $first->seo_title ?: $seoTitle;
                    $seoDescription = $first->seo_description ?: $seoDescription;
                    $seoKeywords = $first->seo_keywords ?: $seoKeywords;
                }
            } elseif (is_object($eventTemplate)) {
                $seoTitle = $eventTemplate->seo_title ?: $seoTitle;
                $seoDescription = $eventTemplate->seo_description ?: $seoDescription;
                $seoKeywords = $eventTemplate->seo_keywords ?: $seoKeywords;
            }
        }
    @endphp
    <title>{{ $seoTitle }}</title>
    <meta name="description" content="{{ $seoDescription }}">
    <meta name="keywords" content="{{ $seoKeywords }}">


    <link rel="icon" type="image/ico" href="{{ asset('uploads/favicon.ico') }}">

    <!-- All CSS -->
    <link rel="stylesheet" href="{{ asset('dist-front/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist-front/css/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist-front/css/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist-front/css/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('dist-front/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist-front/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist-front/css/select2-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist-front/css/all.css') }}">
    <link rel="stylesheet" href="{{ asset('dist-front/css/meanmenu.css') }}">
    <link rel="stylesheet" href="{{ asset('dist-front/css/spacing.css') }}">
    <link rel="stylesheet" href="{{ asset('dist-front/css/style.css') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,100..900;1,100..900&display=swap">

    <!-- All Javascripts -->
    <script src="{{ asset('dist-front/js/jquery-3.6.1.min.js') }}"></script>
    <script src="{{ asset('dist-front/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('dist-front/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('dist-front/js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('dist-front/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('dist-front/js/wow.min.js') }}"></script>
    <script src="{{ asset('dist-front/js/select2.full.js') }}"></script>
    <script src="{{ asset('dist-front/js/jquery.waypoints.min.js') }}"></script>
    <script src="{{ asset('dist-front/js/moment.min.js') }}"></script>
    <script src="{{ asset('dist-front/js/counterup.min.js') }}"></script>
    <script src="{{ asset('dist-front/js/multi-countdown.js') }}"></script>
    <script src="{{ asset('dist-front/js/jquery.meanmenu.js') }}"></script>
    <script src="https://kit.fontawesome.com/758b8e5b95.js" crossorigin="anonymous"></script>

    @php
        $current_region_id = (request()->region_id ?? request()->cookie('region_id', 16));
    @endphp

    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
{{-- <div class="top">
    <div class="container">
        <div class="row">
            <div class="col-md-6 left-side">
                <ul>
                    <li class="phone-text"><i class="fas fa-phone"></i> +48 606 102 243</li>
                    <li class="email-text"><i class="fas fa-envelope"></i> rafa@bprafa.pl</li>
                </ul>
            </div>
            <div class="col-md-6 right-side">
                <ul class="right">
                    <ul class="social">
                        <li><a style="font-size: 20px" href="https://www.facebook.com/biuropodrozyrafa/" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
                        <li><a style="font-size: 20px" href="https://www.instagram.com/biuropodrozyrafa/" target="_blank"><i class="fab fa-instagram"></i></a></li>
                        <li class="menu">
                        <a href="{{ route('login') }}"><i class="fas fa-sign-in-alt"></i> Logowanie</a>
                    </li>
                    <li class="menu">
                        <a href="{{ route('registration') }}"><i class="fas fa-user"></i> Rejestracja</a>
                    </li> --}}
                </ul>
                </ul>
            </div>
        </div>
    </div>
</div>

@include('front.layout.nav')

@yield('main_content')

<div class="footer pt_70">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-4">
                <div class="item pb_50">
                    <h2 class="heading">Na skróty:</h2>
                    <ul class="useful-links">
                        {{-- TODO: Convert back to dynamic routes --}}
                        <li><a href="#"><i class="fas fa-angle-right"></i> Strona główna</a></li>
                        <li><a href="#"><i class="fas fa-angle-right"></i> Pełna oferta wycieczek 2024/2025</a></li>
                        <li><a href="#"><i class="fas fa-angle-right"></i> Aktualności</a></li>
                        {{-- Original dynamic routes:
                        <li><a href="{{ route('home') }}"><i class="fas fa-angle-right"></i> Strona główna</a></li>
                        <li><a href="{{ route('packages') }}"><i class="fas fa-angle-right"></i> Pełna oferta wycieczek 2024/2025</a></li>
                        <li><a href="{{ route('blog') }}"><i class="fas fa-angle-right"></i> Aktualności</a></li>
                        --}}
                    </ul>
                </div>
            </div>
            <div class="col-lg-4 col-md-4">
                <div class="item pb_50">
                    <h2 class="heading">Przydatne linki:</h2>
                    <ul class="useful-links">
                        {{-- TODO: Convert back to dynamic routes --}}
                        <li><a href="#"><i class="fas fa-angle-right"></i> FAQ</a></li>
                        <li><a href="#"><i class="fas fa-angle-right"></i> Dokumenty</a></li>
                        <li><a href="#"><i class="fas fa-angle-right"></i> Ubezpieczenia</a></li>
                        <li><a href="#"><i class="fas fa-angle-right"></i> Kontakt</a></li>
                        {{-- Original dynamic routes:
                        <li><a href="faq.html"><i class="fas fa-angle-right"></i> FAQ</a></li>
                        <li><a href="terms.html"><i class="fas fa-angle-right"></i> Dokumenty</a></li>
                        <li><a href="{{ route('documents') }}"><i class="fas fa-angle-right"></i> Ubezpieczenia</a></li>
                        <li><a href="contact.html"><i class="fas fa-angle-right"></i> Kontakt</a></li>
                        --}}
                    </ul>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="item pb_50">
                    <h2 class="heading">Kontakt</h2>
                    <div class="list-item">
                        <div class="left">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="right">
                            Marii Konopnickiej 6, 00-491 Warszawa
                        </div>
                    </div>
                    <div class="list-item">
                        <div class="left">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="right">rafa@bprafa.pl</div>
                    </div>
                    <div class="list-item">
                        <div class="left">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="right">+48 606 102 243</div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="footer-bottom">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="copyright">
                    Copyright &copy; 2024, Biuro Podróży RAFA. All Rights Reserved.
                </div>
            </div>
        </div>
    </div>
</div>

<div class="scroll-top">
    <i class="fas fa-angle-up"></i>
</div>


<script src="{{ asset('dist-front/js/custom.js') }}"></script>

@if($errors->any())
    @foreach ($errors->all() as $error)
        <script>
            console.error('{{ $error }}');
            // Można dodać własny system powiadomień
        </script>
    @endforeach
@endif

@if(session('success'))
    <script>
        console.log('{{ session("success") }}');
        // Można dodać własny system powiadomień
    </script>
@endif

@if(session('error'))
    <script>
        console.error('{{ session("error") }}');
        // Można dodać własny system powiadomień
    </script>
@endif

</body>
</html>
