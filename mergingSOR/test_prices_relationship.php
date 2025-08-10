<?php
// Uruchom ten plik przez: php test_prices_relationship.php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\EventTemplate;
use App\Models\EventTemplatePricePerPerson;

echo "Test relacji pricesPerPerson:\n";

// Sprawdź pierwszy EventTemplate
$et = EventTemplate::first();
echo "EventTemplate ID: {$et->id}\n";
echo "Nazwa: {$et->name}\n";

// Sprawdź relację
$prices = $et->pricesPerPerson;
echo "Liczba rekordów w pricesPerPerson: {$prices->count()}\n";

if ($prices->count() > 0) {
    echo "Min price_per_person: {$prices->min('price_per_person')}\n";
    echo "Wszystkie ceny: " . $prices->pluck('price_per_person')->implode(', ') . "\n";
} else {
    echo "Brak rekordów w pricesPerPerson dla tego EventTemplate\n";
    
    // Sprawdź czy są jakieś rekordy dla tego event_template_id w tabeli
    $directPrices = EventTemplatePricePerPerson::where('event_template_id', $et->id)->get();
    echo "Rekordy bezpośrednio z tabeli: {$directPrices->count()}\n";
    if ($directPrices->count() > 0) {
        echo "Direct prices: " . $directPrices->pluck('price_per_person')->implode(', ') . "\n";
    }
}

echo "\nTest dla wszystkich EventTemplate (pierwsze 5):\n";
$eventTemplates = EventTemplate::take(5)->get();
foreach ($eventTemplates as $template) {
    $count = $template->pricesPerPerson->count();
    $minPrice = $count > 0 ? $template->pricesPerPerson->min('price_per_person') : 'brak';
    echo "ID: {$template->id}, Nazwa: {$template->name}, Prices count: {$count}, Min price: {$minPrice}\n";
}
