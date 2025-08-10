<?php
namespace App\Jobs;

use App\Models\PlaceDistance;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdatePlaceDistancesBatch implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $ids;
    protected $apiKey;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $ids, $apiKey)
    {
        $this->ids = $ids;
        $this->apiKey = $apiKey;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $updated = 0;
        $total = count($this->ids);
        $batch = PlaceDistance::whereIn('id', $this->ids)->get();
        foreach ($batch as $i => $distanceRow) {
            $from = $distanceRow->fromPlace;
            $to = $distanceRow->toPlace;
            if (!$from || !$to) continue;
            // Nie dubluj zapytań do API
            if ($distanceRow->distance_km !== null) continue;
            $distance = $this->fetchDistance($from, $to, $this->apiKey);
            if ($distance !== null) {
                $distanceRow->distance_km = $distance;
                $distanceRow->api_source = 'openrouteservice';
                $distanceRow->save();
                $updated++;
            }
            // Loguj postęp co 10 rekordów
            if ((($i+1) % 10 === 0) || ($i+1) === $total) {
                \Illuminate\Support\Facades\Log::info("[UpdatePlaceDistancesBatch] Postęp batcha: ".($i+1)."/".$total);
            }
        }
        \Illuminate\Support\Facades\Log::info("[UpdatePlaceDistancesBatch] Zaktualizowano $updated odległości w batchu ($total)");
    }

    protected function fetchDistance($from, $to, $apiKey)
    {
        if (!$from->latitude || !$from->longitude || !$to->latitude || !$to->longitude) return null;
        $url = 'https://api.openrouteservice.org/v2/directions/driving-car?api_key=' . $apiKey . '&start=' . $from->longitude . ',' . $from->latitude . '&end=' . $to->longitude . ',' . $to->latitude;
        try {
            $response = file_get_contents($url);
            $data = json_decode($response, true);
            if (isset($data['features'][0]['properties']['segments'][0]['distance'])) {
                return round($data['features'][0]['properties']['segments'][0]['distance'] / 1000, 2);
            }
        } catch (\Exception $e) {
            return null;
        }
        return null;
    }
}
