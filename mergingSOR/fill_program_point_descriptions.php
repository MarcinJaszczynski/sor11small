<?php
// Uruchom ten plik przez: php fill_program_point_descriptions.php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

function generateDescription($name) {
    $templates = [
        "To wyjątkowy punkt programu: %s. Uczestnicy mogą liczyć na niezapomniane wrażenia.",
        "Program obejmuje: %s. To doskonała okazja do integracji i zabawy.",
        "W ramach tego punktu: %s. Zapewniamy profesjonalną organizację i miłą atmosferę.",
        "%s to propozycja dla osób ceniących aktywność i nowe doświadczenia.",
        "Podczas: %s uczestnicy poznają ciekawe miejsca i lokalne atrakcje.",
        "%s to gwarancja dobrej zabawy i niezapomnianych chwil.",
        "W tym punkcie programu: %s. Każdy znajdzie coś dla siebie.",
        "%s pozwala na rozwijanie pasji i zainteresowań w grupie.",
        "%s to idealny wybór na udaną wycieczkę szkolną.",
        "Realizacja punktu: %s sprawia, że wyjazd staje się jeszcze bardziej atrakcyjny.",
        "%s to doskonała okazja do poznania nowych ludzi i miejsc.",
        "Uczestnictwo w %s wzbogaca program o ciekawe doświadczenia.",
        "%s to element, który wyróżnia naszą ofertę na tle innych biur podróży.",
        "Dzięki %s każdy dzień wycieczki nabiera wyjątkowego charakteru.",
        "%s to propozycja dla osób lubiących aktywny wypoczynek.",
        "%s pozwala na odkrywanie lokalnych tradycji i kultury.",
        "W trakcie %s uczestnicy mają okazję do nauki i zabawy.",
        "%s to punkt, który cieszy się dużą popularnością wśród naszych klientów.",
        "%s to gwarancja niezapomnianych wspomnień z wyjazdu.",
        "%s sprawia, że program jest zróżnicowany i interesujący dla wszystkich.",
        "%s to doskonały sposób na spędzenie czasu w gronie rówieśników.",
        "%s pozwala na rozwijanie umiejętności i zdobywanie nowych doświadczeń.",
        "%s to punkt, który wzbogaca każdą wycieczkę szkolną.",
        "%s to propozycja dla osób otwartych na nowe wyzwania.",
        "%s to element, który uatrakcyjnia program wyjazdu.",
        "%s to doskonała okazja do relaksu i odpoczynku po intensywnym dniu.",
        "%s to punkt, który integruje uczestników i buduje pozytywną atmosferę.",
        "%s to propozycja dla miłośników przygód i aktywności na świeżym powietrzu.",
        "%s to punkt, który pozwala na odkrywanie nowych pasji i zainteresowań.",
    ];
    $sentences = collect($templates)->shuffle()->take(rand(1,3))->map(fn($tpl) => sprintf($tpl, $name));
    return $sentences->implode(' ');
}

$points = DB::table('event_template_program_points')->get();
foreach ($points as $point) {
    $desc = generateDescription($point->name);
    DB::table('event_template_program_points')->where('id', $point->id)->update(['description' => $desc]);
    echo "ID: {$point->id} -> description: {$desc}\n";
}
echo "Opisy zostały uzupełnione.\n";
