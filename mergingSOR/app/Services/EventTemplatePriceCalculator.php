<?php

namespace App\Services;

use App\Models\EventTemplate;
use App\Models\EventTemplateQty;
use App\Models\EventTemplatePricePerPerson;
use App\Models\Currency;
use Illuminate\Support\Facades\DB;

class EventTemplatePriceCalculator
{
    public function calculateAndSave(EventTemplate $eventTemplate): void
    {
        $programPoints = $eventTemplate->programPoints()
            ->with(['currency', 'children.currency'])
            ->wherePivot('include_in_calculation', true)
            ->get();

        $qtyVariants = \App\Models\EventTemplateQty::all();
        $currencies = collect();
        foreach ($programPoints as $point) {
            if ($point->currency) {
                $currencies->push($point->currency);
            }
            foreach ($point->children as $child) {
                if ($child->currency) {
                    $currencies->push($child->currency);
                }
            }
        }
        $currencies = $currencies->unique('id');

        foreach ($qtyVariants as $qtyVariant) {
            $qty = $qtyVariant->qty;
            foreach ($currencies as $currency) {
                $total = 0;
                // Główne punkty
                foreach ($programPoints->where('currency_id', $currency->id) as $point) {
                    $groupSize = $point->group_size ?? 1;
                    $unitPrice = $point->unit_price ?? 0;
                    $pointPrice = ceil($qty / $groupSize) * $unitPrice;
                    $total += $pointPrice;
                }
                // Podpunkty
                foreach ($programPoints as $point) {
                    foreach ($point->children->where('currency_id', $currency->id) as $child) {
                        $groupSize = $child->group_size ?? 1;
                        $unitPrice = $child->unit_price ?? 0;
                        $childPrice = ceil($qty / $groupSize) * $unitPrice;
                        $total += $childPrice;
                    }
                }
                if ($qty > 0) {
                    $pricePerPerson = ceil($total / $qty);
                } else {
                    $pricePerPerson = 0;
                }
                EventTemplatePricePerPerson::updateOrCreate([
                    'event_template_id' => $eventTemplate->id,
                    'event_template_qty_id' => $qtyVariant->id,
                    'currency_id' => $currency->id,
                ], [
                    'price_per_person' => $pricePerPerson,
                    'updated_at' => now(), // wymusza aktualizację znacznika czasu
                ]);
            }
        }
    }
}
