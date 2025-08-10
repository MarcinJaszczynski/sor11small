<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== KONWERSJA PLIKU ODLEGŁOŚCI.CSV - POPRAWNE KODOWANIE ===\n\n";

$inputFile = 'zrodla/Odległości.csv';
$outputFile = 'storage/app/public/miejsca_docelowe_import.csv';

if (!file_exists($inputFile)) {
    echo "Błąd: Plik {$inputFile} nie istnieje!\n";
    exit(1);
}

// Otwórz plik wejściowy z poprawnym kodowaniem
$content = file_get_contents($inputFile);
$content = mb_convert_encoding($content, 'UTF-8', 'auto');
$lines = explode("\n", $content);

// Otwórz plik wyjściowy
$output = fopen($outputFile, 'w');
if (!$output) {
    echo "Błąd: Nie można utworzyć pliku {$outputFile}!\n";
    exit(1);
}

// Zapisz nagłówek
fputcsv($output, ['nazwa', 'opis', 'tagi', 'miejsce_poczatkowe', 'szerokosc_geograficzna', 'dlugosc_geograficzna']);

$lineNumber = 0;
$processedCount = 0;

foreach ($lines as $line) {
    $lineNumber++;
    
    if (empty(trim($line))) {
        continue;
    }
    
    $row = str_getcsv($line, ';');
    
    // Pomiń nagłówek
    if ($lineNumber == 1) {
        continue;
    }
    
    // Sprawdź czy wiersz ma wystarczającą liczbę kolumn
    if (count($row) < 4) {
        echo "Pominięto wiersz {$lineNumber}: za mało kolumn\n";
        continue;
    }
    
    $miasto = trim($row[1]);
    $adres = trim($row[2] ?? '');
    $wspolrzedne = trim($row[3]);
    $nazwaWycieczki = trim($row[4] ?? '');
    
    if (empty($miasto) || empty($wspolrzedne)) {
        echo "Pominięto wiersz {$lineNumber}: brak nazwy miasta lub współrzędnych\n";
        continue;
    }
    
    // Parsuj współrzędne (format: "52.029510, 23.125263")
    $coords = array_map('trim', explode(',', $wspolrzedne));
    if (count($coords) != 2) {
        echo "Pominięto wiersz {$lineNumber}: nieprawidłowy format współrzędnych: {$wspolrzedne}\n";
        continue;
    }
    
    $latitude = $coords[0];
    $longitude = $coords[1];
    
    // Sprawdź czy współrzędne są liczbami
    if (!is_numeric($latitude) || !is_numeric($longitude)) {
        echo "Pominięto wiersz {$lineNumber}: współrzędne nie są liczbami: {$wspolrzedne}\n";
        continue;
    }
    
    // Przygotuj opis - połącz adres i nazwę wycieczki
    $opisParts = array_filter([
        $adres ? "Adres: {$adres}" : '',
        $nazwaWycieczki ? "Wycieczka: {$nazwaWycieczki}" : ''
    ]);
    $opis = implode(' | ', $opisParts);
    
    // Przygotuj tagi na podstawie nazwy wycieczki
    $tagi = ['miejsce_docelowe'];
    if ($nazwaWycieczki) {
        // Dodaj nazwę wycieczki jako tag (bez polskich znaków)
        $tag = strtolower($nazwaWycieczki);
        $tag = str_replace(['ą', 'ć', 'ę', 'ł', 'ń', 'ó', 'ś', 'ź', 'ż'], 
                          ['a', 'c', 'e', 'l', 'n', 'o', 's', 'z', 'z'], $tag);
        $tag = str_replace([' ', ',', '-', '/', '(', ')', '.'], '_', $tag);
        $tagi[] = $tag;
    }
    
    // Dodaj region/województwo na podstawie nazwy miejsca
    $regiony = [
        'Kraków' => 'malopolskie',
        'Krakow' => 'malopolskie',
        'krakow' => 'malopolskie',
        'Warszawa' => 'mazowieckie',
        'warszawa' => 'mazowieckie',
        'Gdańsk' => 'pomorskie',
        'Gdansk' => 'pomorskie',
        'gdansk' => 'pomorskie',
        'Wrocław' => 'dolnoslaskie',
        'Wroclaw' => 'dolnoslaskie',
        'wroclaw' => 'dolnoslaskie',
        'Poznań' => 'wielkopolskie',
        'Poznan' => 'wielkopolskie',
        'poznan' => 'wielkopolskie',
        'Lublin' => 'lubelskie',
        'lublin' => 'lubelskie',
        'Katowice' => 'slaskie',
        'katowice' => 'slaskie',
        'Białystok' => 'podlaskie',
        'Bialystok' => 'podlaskie',
        'bialystok' => 'podlaskie',
        'Kielce' => 'swietokrzyskie',
        'kielce' => 'swietokrzyskie',
        'Olsztyn' => 'warminsko_mazurskie',
        'olsztyn' => 'warminsko_mazurskie',
        'Rzeszów' => 'podkarpackie',
        'Rzeszow' => 'podkarpackie',
        'rzeszow' => 'podkarpackie',
        'Szczecin' => 'zachodniopomorskie',
        'szczecin' => 'zachodniopomorskie',
        'Bydgoszcz' => 'kujawsko_pomorskie',
        'bydgoszcz' => 'kujawsko_pomorskie',
        'Zielona' => 'lubuskie',
        'zielona' => 'lubuskie',
        'Łódź' => 'lodzkie',
        'Lodz' => 'lodzkie',
        'lodz' => 'lodzkie',
        'Opole' => 'opolskie',
        'opole' => 'opolskie'
    ];
    
    foreach ($regiony as $miastoRegion => $wojewodztwo) {
        if (stripos($miasto, $miastoRegion) !== false || stripos($nazwaWycieczki, $miastoRegion) !== false) {
            $tagi[] = $wojewodztwo;
            break;
        }
    }
    
    // Zapisz do pliku CSV
    fputcsv($output, [
        $miasto,
        $opis,
        implode(';', $tagi),
        'nie', // miejsce_poczatkowe = false
        $latitude,
        $longitude
    ]);
    
    $processedCount++;
}

fclose($output);

echo "Konwersja zakończona!\n";
echo "Przetworzono: {$processedCount} miejsc docelowych\n";
echo "Plik wyjściowy: {$outputFile}\n";
echo "\nTeraz możesz zaimportować oba pliki przez panel administracyjny:\n";
echo "1. storage/app/public/miejsca_startowe_import.csv (miejsca startowe)\n";
echo "2. storage/app/public/miejsca_docelowe_import.csv (miejsca docelowe)\n";

?>
