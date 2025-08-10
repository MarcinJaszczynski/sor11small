<?php

namespace App\Exports;

use App\Models\Place;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PlacesExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    public function collection()
    {
        return Place::all();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nazwa',
            'Opis',
            'Tagi',
            'Miejsce początkowe',
            'Szerokość geograficzna',
            'Długość geograficzna',
            'Utworzono',
            'Zaktualizowano',
        ];
    }

    public function map($place): array
    {
        return [
            $place->id,
            $place->name,
            $place->description,
            is_array($place->tags) ? implode(';', $place->tags) : $place->tags,
            $place->starting_place ? 'Tak' : 'Nie',
            $place->latitude,
            $place->longitude,
            $place->created_at?->format('Y-m-d H:i:s'),
            $place->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
