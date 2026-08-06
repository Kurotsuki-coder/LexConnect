<?php

namespace App\Observers;

use App\Models\Avis;
use App\Services\ScoreAvocatService;

class AvisObserver
{
    public function __construct(
        private ScoreAvocatService $scoreService
    ) {}

    public function created(Avis $avis): void
    {
        $this->scoreService->recalculerEtSauvegarder($avis->id_avocat);
    }

    public function updated(Avis $avis): void
    {
        $this->scoreService->recalculerEtSauvegarder($avis->id_avocat);
    }

    public function deleted(Avis $avis): void
    {
        $this->scoreService->recalculerEtSauvegarder($avis->id_avocat);
    }
}