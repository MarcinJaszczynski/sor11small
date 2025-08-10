<!-- iziToast CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast@1.4.0/dist/css/iziToast.min.css">
<!-- iziToast JS -->
<script src="https://cdn.jsdelivr.net/npm/izitoast@1.4.0/dist/js/iziToast.min.js"></script>

@if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            iziToast.success({
                title: 'Sukces',
                message: '{{ session('success') }}',
                position: 'topRight',
                timeout: 4000,
                close: true,
                progressBar: true
            });
        });
    </script>
@endif
@extends('front.layout.master')

@section('main_content')

    <div class="page-top">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="breadcrumb-container">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Start</a></li>
                            <li class="breadcrumb-item active">Kontakt</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container pt_50">
        <div class="contact-divide-box">
            <div class="contact-section-left">
                <div class="header"><h1>Kontakt</h1></div>
                <div class="description">
                    <a href="tel:606102243"><div class="phone">
                            <i class="fas fa-phone"></i>+ 48 606 102 243
                        </div></a>
                    <a href="mailto:rafa@bprafa.pl"><div class="mail">
                            <i class="fas fa-envelope"></i> rafa@bprafa.pl
                        </div></a>

<div class="adress">
    <h1 class="header">Biuro Podróży Rafa</h1>
    <div><i class="fas fa-map-marker-alt"></i> Marii Konopnickiej 6,</div>
    <div>00-491 Warszawa</div>
</div>
<div class="map" style="width:100%; height:min(40vw,350px); margin-top:1em; padding-right:2em; padding-top:1em;">
    <iframe
        src="https://www.google.com/maps?q=Marii+Konopnickiej+6,+Warszawa&output=embed"
        style="border:0; display:block; width:100%; height:100%; min-height:120px;"
        allowfullscreen="" loading="lazy"
        referrerpolicy="no-referrer-when-downgrade"></iframe>
</div>
            </div>
            </div>
            <div class="contact-section-right">
            <form class="contact-form" method="POST" action="{{ route('send-email') }}">
                @csrf <!-- Laravel CSRF Protection -->
                <h4>Skontaktuj się z nami!</h4>
                <label for="name">Imię i nazwisko:</label>
                <input type="text" name="name" id="name" placeholder="Wpisz imię i nazwisko">
                <small class="error"></small>

                <label for="email">Adres email: <span class="required">*</span></label>
                <input type="text" name="email" id="email" placeholder="Wpisz adres email">
                <small class="error"></small>

                <label for="telephone">Numer telefonu: <span class="required">*</span></label>
                <input type="text" name="telephone" id="telephone" placeholder="Wpisz numer telefonu">
                <small class="error"></small>

                <label for="message">Treść wiadomości:</label>
                <textarea id="message" name="message" rows="15" placeholder="Wpisz treść wiadomości"></textarea>
                <small class="error"></small>

                <div class="center">
                    <input type="submit" value="Wyślij">
                    <p id="success"></p>
                </div>
            </form>
            </div>
        </div>

    </div>

@endsection
