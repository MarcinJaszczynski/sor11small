@extends('front.layout.master')

@section('main_content')
    <div class="page-top">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="breadcrumb-container">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Start</a></li>
                            <li class="breadcrumb-item active">Dokumenty</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container" style="padding-top: 50px;">
        <div class="documents-photo">
            <img src="{{ asset('uploads/slider_1734973897.jpg') }}" alt="" style="object-position: center 25%; object-fit: cover;">
        </div>
    </div>

    <div class="documents pt_50 pb_70">
        <div class="container">
            <div class="documents-cards" style="display: block !important; margin: 0 auto; max-width: 100%;">
                <div style="margin: 0 auto; max-width: 100%;">

            <style>
                .document-card {
                    margin-bottom: 2rem;
                }
                .document-card:last-child {
                    margin-bottom: 0;
                }
                .documents-cards h4:first-of-type {
                    padding-top: 2rem;
                }
                .documents-photo {
                    width: 100% !important;
                    aspect-ratio: 4/1 !important;
                    overflow: hidden !important;
                    border-radius: 30px !important;
                }
                .documents-photo img {
                    width: 100% !important;
                    height: 100% !important;
                    object-fit: cover !important;
                }

            </style>
            <style>
            @media (max-width: 767.98px) {
                .documents-photo {
                    aspect-ratio: 2/1 !important;
                }
                .document-card {
                    display: flex !important;
                    flex-direction: column !important;
                    width: 100% !important;
                    box-sizing: border-box;
                }
                .document-card .buttons {
                    display: flex !important;
                    flex-direction: column !important;
                    width: 100% !important;
                    box-sizing: border-box;
                }
                .document-card .buttons + .buttons {
                    margin-top: 1rem;
                }
                .document-card .document-cards-link {
                    width: 100% !important;
                    box-sizing: border-box;
                }
            }
            </style>

            <h4><b>Warunki uczestnictwa</b></h4>
            <div class="document-card">
            <div class="buttons">
                <div class="document-cards-link">Warunki uczestnictwa w imprezach organizowanych przez Biuro Podróży RAFA – do umów zawartych od 01.01.2025&nbsp;<a href="/uploads/documents/warunki_uczestnictwa.pdf" target="_blank" class="redirect-arrow"><i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
            <div class="buttons">
                <div class="document-cards-link">Regulamin przewozu osób autokarem podczas wycieczek organizowanych przez Biuro Podróży RAFA&nbsp;<a href="/uploads/documents/regulamin_przewozu.pdf" target="_blank" class="redirect-arrow"><i class="fas fa-arrow-right"></i></a></div>
            </div>
            </div>

            <h4><b>CEIDG, Wpis do rejestru</b></h4>
            <div class="document-card">
                <div class="buttons">
                    <div class="document-cards-link">CEIDG<a href="/uploads/documents/ceidg.pdf" target="_blank" class="redirect-arrow"><i class="fas fa-arrow-right"></i></a></div>
                </div>
                <div class="buttons">
                    <div class="document-cards-link">Wpis do rejestru Organizatorów Turystyki<a href="/uploads/documents/wpis_do_rejestru.pdf" target="_blank" class="redirect-arrow"><i class="fas fa-arrow-right"></i></a></div>
                </div>
            </div>
            <h4><b>Ochrona uczestników</b></h4>
            <div class="document-card">
                <div class="buttons">
                    <div class="document-cards-link"> Procedury Ochrony Małoletnich/Dzieci Podczas Wycieczek Organizowanych Przez Biuro Podróży „RAFA”<a href="/uploads/documents/procedury_ochrony.pdf" target="_blank" class="redirect-arrow"><i class="fas fa-arrow-right"></i></a></div>
                </div>
                <div class="buttons">
                    <div class="document-cards-link">Polityka RODO<a href="/uploads/documents/polityka_rodo.pdf" target="_blank" class="redirect-arrow"><i class="fas fa-arrow-right"></i></a></div>
                </div>
            </div>

            <h4><b>Ustawy</b></h4>
            <div class="document-card">
                <div class="buttons">
                    <div class="document-cards-link">Ustawa o imprezach turystycznych<a href="/uploads/documents/ustawa_imprezy_turystyczne.pdf" target="_blank" class="redirect-arrow"><i class="fas fa-arrow-right"></i></a></div>
                </div>
                <div class="buttons">
                    <div class="document-cards-link">Standardowy Formularz Informacyjny<a href="/uploads/documents/formularz_informacyjny.pdf" target="_blank" class="redirect-arrow"><i class="fas fa-arrow-right"></i></a></div>
                </div>
            </div>
            <!-- <div class="document-card">
                <h4><b>Faktura</b></h4>
                <div class="buttons">
                    <div class="document-cards-link">Wniosek o fakturę na firmę</div>
                </div>
                <div class="buttons">
                    <div class="document-cards-link">Wniosek o fakturę na osobę fizyczną
                    </div>
                </div>
            </div> -->
                </div>
            </div>
        </div>
    </div>

@endsection
