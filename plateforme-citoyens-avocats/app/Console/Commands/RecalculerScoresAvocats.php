<?php

namespace App\Console\Commands;

use App\Services\ScoreAvocatService;
use Illuminate\Console\Command;

class RecalculerScoresAvocats extends Command
{
    protected $signature = 'avocats:recalculer-scores';
    protected $description = 'Recalcule le score bayésien pondéré par ancienneté de tous les avocats';

    public function handle(ScoreAvocatService $scoreService): int
    {
        $this->info('Recalcul des scores en cours...');

        $debut = microtime(true);
        $count = $scoreService->recalculerTout();
        $duree = round(microtime(true) - $debut, 2);

        $this->info("Terminé : {$count} avocats recalculés en {$duree}s.");

        return self::SUCCESS;
    }
}