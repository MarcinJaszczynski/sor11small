<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model EventTemplatePricePerPerson
 * Reprezentuje cenę za osobę dla wariantu ilości uczestników w szablonie wydarzenia.
 *
 * @property int $id
 * @property int $event_template_id
 * @property int $event_template_qty_id
 * @property int $currency_id
 * @property float $price_per_person
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class EventTemplatePricePerPerson extends Model
{
    protected $table = 'event_template_price_per_person';
    /**
     * Pola masowo przypisywalne
     * @var array<int, string>
     */
    protected $fillable = [
        'event_template_id',
        'event_template_qty_id',
        'currency_id',
        'price_per_person',
    ];

    /**
     * Relacja do szablonu wydarzenia
     */
    public function eventTemplate()
    {
        return $this->belongsTo(EventTemplate::class);
    }
    /**
     * Relacja do wariantu ilości uczestników
     */
    public function eventTemplateQty()
    {
        return $this->belongsTo(EventTemplateQty::class);
    }
    /**
     * Relacja do waluty
     */
    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
}
