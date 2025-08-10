<div class="navbar-area" id="stickymenu">
    <!-- Menu For Mobile Device -->
    <div class="mobile-nav">
        <a href="{{ route('home') }}" class="logo">
            <img src="{{ asset('uploads/logo.png') }}" alt="">
        </a>
    </div>

    <!-- Menu For Desktop Device -->
    <div class="main-nav">
        <div class="container">
            <nav class="navbar navbar-expand-md navbar-light">
                {{-- TODO: Convert back to dynamic route --}}
                <a class="navbar-brand" href="{{ route('home') }}">
                    <img src="{{ asset('uploads/logo.png') }}" alt="">
                </a>
                {{-- Original: <a class="navbar-brand" href="{{ url('/?region_id=' . (request()->region_id ?? request()->cookie('region_id', 16))) }}"> --}}
                <div class="collapse navbar-collapse mean-menu" id="navbarSupportedContent">
                    <ul class="navbar-nav">
                        <li class="nav-item {{ Route::is('home') ? 'active' : '' }}">
                            <a href="{{ route('home') }}" class="nav-link" class="nav-link">Start</a>
                        </li>
                        <div class="offer-link">
                            <li class="nav-item">
                                {{-- TODO: Convert back to dynamic routes --}}
                                <a href="{{ route('directory-packages')}}" class="nav-link">Oferta wycieczek szkolnych 2025</a>
                                <ul class="dropdown">
                                    <div class="background">
                                        <li>
                                            <a href="{{ route('packages', ['length_id' => '1']) }}">Wycieczki jednodniowe</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('packages', ['length_id' => '2']) }}">Wycieczki dwudniowe</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('packages', ['length_id' => '3']) }}">Wycieczki trzydniowe</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('packages', ['length_id' => '4']) }}">Wycieczki czterodniowe</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('packages', ['length_id' => '5']) }}">Wycieczki pięciodniowe</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('packages', ['length_id' => '6plus']) }}">Wycieczki sześciodniowe i dłuższe</a>
                                        </li>
                                        {{-- Original dynamic URLs:
                                        <li><a href="{{ url('/packages?region_id=' . (request()->region_id ?? request()->cookie('region_id', 16)) . '&length_id=1') }}">Wycieczki jednodniowe</a></li>
                                        <li><a href="{{ url('/packages?region_id=' . (request()->region_id ?? request()->cookie('region_id', 16)) . '&length_id=2') }}">Wycieczki dwudniowe</a></li>
                                        <li><a href="{{ url('/packages?region_id=' . (request()->region_id ?? request()->cookie('region_id', 16)) . '&length_id=3') }}">Wycieczki trzydniowe</a></li>
                                        <li><a href="{{ url('/packages?region_id=' . (request()->region_id ?? request()->cookie('region_id', 16)) . '&length_id=4') }}">Wycieczki czterodniowe</a></li>
                                        <li><a href="{{ url('/packages?region_id=' . (request()->region_id ?? request()->cookie('region_id', 16)) . '&length_id=5') }}">Wycieczki pięciodniowe</a></li>
                                        <li><a href="{{ url('/packages?region_id=' . (request()->region_id ?? request()->cookie('region_id', 16)) . '&length_id=6') }}">Wycieczki sześciodniowe i dłuższe</a></li>
                                        --}}
                                    </div>
                                </ul>
                            </li>
                        </div>
                        {{-- TODO: Convert back to dynamic routes and Route::is() checks --}}
                        <li class="nav-item">
                             <a href="{{ route('blog') }}" class="nav-link">Aktualności</a>
                        </li>
                        <li class="nav-item {{ Route::is('insurance') ? 'active' : '' }}">
                            <a href="{{ route('insurance') }}" class="nav-link">Ubezpieczenia</a>
                        </li>
                        <li class="nav-item {{ Route::is('documents') ? 'active' : '' }}">
                            <a href="{{ route('documents') }}" class="nav-link">Dokumenty</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('contact') }}" class="nav-link">Kontakt</a>
                        </li>
                        {{-- Original dynamic routes:
                        <li class="nav-item {{ Route::is('blog') ? 'active' : '' }}">
                             <a href="{{ route('blog') }}" class="nav-link">Aktualności</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('insurance') }}" class="nav-link">Ubezpieczenia</a>
                        </li>
                        <li class="nav-item {{ Route::is('documents') ? 'active' : '' }}">
                            <a href="{{ route('documents') }}" class="nav-link">Dokumenty</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('contact') }}" class="nav-link">Kontakt</a>
                        </li>
                        --}}
                    </ul>
                </div>
                <div class="contact-info">
                    <a href="tel:606102243"><div class="phone">
                        <i class="fas fa-phone"></i>+ 48 606 102 243
                        </div></a>
                    <a href="mailto:rafa@bprafa.pl"><div class="mail">
                        <i class="fas fa-envelope"></i> rafa@bprafa.pl
                    </div></a>
                </div>
            </nav>
        </div>
    </div>
</div>
