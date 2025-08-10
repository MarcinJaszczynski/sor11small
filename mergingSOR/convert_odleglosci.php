<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== KONWERSJA PLIKU ODLEGŁOŚCI.CSV ===\n\n";

$inputFile = 'zrodla/Odległości.csv';
$outputFile = 'storage/app/public/miejsca_docelowe_import.csv';

if (!file_exists($inputFile)) {
    echo "Błąd: Plik {$inputFile} nie istnieje!\n";
    exit(1);
}

// Otwórz plik wejściowy
$input = fopen($inputFile, 'r');
if (!$input) {
    echo "Błąd: Nie można otworzyć pliku {$inputFile}!\n";
    exit(1);
}

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

while (($row = fgetcsv($input, 0, ';')) !== FALSE) {
    $lineNumber++;
    
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
        // Dodaj nazwę wycieczki jako tag
        $tagi[] = strtolower(str_replace([' ', ',', '-'], '_', $nazwaWycieczki));
    }
    
    // Dodaj region/województwo na podstawie nazwy miejsca (uproszczone)
    $regiony = [
        'Kraków' => 'małopolskie',
        'Warszawa' => 'mazowieckie',
        'Gdańsk' => 'pomorskie',
        'Wrocław' => 'dolnośląskie',
        'Poznań' => 'wielkopolskie',
        'Lublin' => 'lubelskie',
        'Katowice' => 'śląskie',
        'Białystok' => 'podlaskie',
        'Kielce' => 'świętokrzyskie',
        'Olsztyn' => 'warmińsko-mazurskie',
        'Rzeszów' => 'podkarpackie',
        'Szczecin' => 'zachodniopomorskie',
        'Bydgoszcz' => 'kujawsko-pomorskie',
        'Zielona Góra' => 'lubuskie',
        'Łódź' => 'łódzkie',
        'Opole' => 'opolskie'
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

fclose($input);
fclose($output);

echo "Konwersja zakończona!\n";
echo "Przetworzono: {$processedCount} miejsc docelowych\n";
echo "Plik wyjściowy: {$outputFile}\n";
echo "\nTeraz możesz zaimportować oba pliki przez panel administracyjny:\n";
echo "1. storage/app/public/miejsca_startowe_import.csv (miejsca startowe)\n";
echo "2. storage/app/public/miejsca_docelowe_import.csv (miejsca docelowe)\n";

?>
