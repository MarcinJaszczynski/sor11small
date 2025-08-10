<?php

namespace App\Imports;

use App\Models\Place;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;

class PlacesImport implements ToModel, WithHeadingRow, WithValidation
{
    use Importable;

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Obsługa tagów - konwersja z tekstu na tablicę
        $tags = [];
        if (!empty($row['tagi'])) {
            $tags = array_filter(array_map('trim', explode(';', $row['tagi'])));
        }

        // Obsługa boolean dla starting_place
        $startingPlace = false;
        if (isset($row['miejsce_poczatkowe'])) {
            $startingPlace = in_array(strtolower($row['miejsce_poczatkowe']), ['tak', 'yes', '1', 'true']);
        }

        return new Place([
            'name' => $row['nazwa'],
            'description' => $row['opis'] ?? null,
            'tags' => $tags,
            'starting_place' => $startingPlace,
            'latitude' => is_numeric($row['szerokosc_geograficzna'] ?? null) ? (float)$row['szerokosc_geograficzna'] : null,
            'longitude' => is_numeric($row['dlugosc_geograficzna'] ?? null) ? (float)$row['dlugosc_geograficzna'] : null,
        ]);
    }

    public function rules(): array
    {
        return [
            'nazwa' => 'required|string|max:255',
            'opis' => 'nullable|string',
            'tagi' => 'nullable|string',
            'miejsce_poczatkowe' => 'nullable|string',
            'szerokosc_geograficzna' => 'nullable|numeric',
            'dlugosc_geograficzna' => 'nullable|numeric',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'nazwa.required' => 'Nazwa miejsca jest wymagana',
            'nazwa.max' => 'Nazwa miejsca nie może być dłuższa niż 255 znaków',
            'szerokosc_geograficzna.numeric' => 'Szerokość geograficzna musi być liczbą',
            'dlugosc_geograficzna.numeric' => 'Długość geograficzna musi być liczbą',
        ];
    }
}
