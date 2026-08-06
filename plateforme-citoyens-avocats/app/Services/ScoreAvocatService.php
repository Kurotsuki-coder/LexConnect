<?php

namespace App\Services;

use App\Models\Avis;
use App\Models\Avocat;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ScoreAvocatService
{
    private const DEMI_VIE_JOURS = 365;
    private const CACHE_TTL = 3600;

    public function moyenneGlobale(): float
    {
        return Cache::remember('avis.moyenne_globale', self::CACHE_TTL, function () {
            return round(Avis::avg('note') ?? 0, 4);
        });
    }

    public function seuilM(): float
    {
        return Cache::remember('avis.seuil_m', self::CACHE_TTL, function () {
            $result = DB::table('avis')
                ->selectRaw('COUNT(*) as total, COUNT(DISTINCT id_avocat) as nb_avocats')
                ->first();

            if (!$result || $result->nb_avocats == 0) {
                return 5.0;
            }

            return round($result->total / $result->nb_avocats, 4);
        });
    }

    public function moyennePondereeTemps(int $idAvocat): array
    {
        $avis = Avis::where('id_avocat', $idAvocat)->get(['note', 'date']);

        if ($avis->isEmpty()) {
            return ['moyenne' => 0.0, 'nb_avis' => 0];
        }

        $sommeNotesPonderees = 0.0;
        $sommePoids = 0.0;

        foreach ($avis as $a) {
            $joursEcoules = now()->diffInDays($a->date);
            $poids = pow(0.5, $joursEcoules / self::DEMI_VIE_JOURS);

            $sommeNotesPonderees += $a->note * $poids;
            $sommePoids += $poids;
        }

        $moyenne = $sommePoids > 0 ? $sommeNotesPonderees / $sommePoids : 0.0;

        return [
            'moyenne' => round($moyenne, 4),
            'nb_avis' => $avis->count(),
        ];
    }

    public function calculerScore(int $idAvocat): array
    {
        $C = $this->moyenneGlobale();
        $m = $this->seuilM();
        $ponderee = $this->moyennePondereeTemps($idAvocat);

        $v = $ponderee['nb_avis'];
        $R = $ponderee['moyenne'];

        // Avocat sans avis => score à 0 pour ne pas passer devant ceux qui ont de vrais avis
        $score = $v === 0
            ? 0.0
            : (($v / ($v + $m)) * $R) + (($m / ($v + $m)) * $C);

        return [
            'score_bayesien' => round($score, 2),
            'note_moyenne_ponderee' => round($R, 2),
            'nb_avis' => $v,
        ];
    }

    public function recalculerEtSauvegarder(int $idAvocat): void
    {
        $resultat = $this->calculerScore($idAvocat);

        Avocat::where('id_avocat', $idAvocat)->update([
            'score_bayesien' => $resultat['score_bayesien'],
            'note_moyenne_ponderee' => $resultat['note_moyenne_ponderee'],
            'nb_avis' => $resultat['nb_avis'],
            'score_calcule_at' => now(),
        ]);
    }

    public function recalculerTout(): int
    {
        Cache::forget('avis.moyenne_globale');
        Cache::forget('avis.seuil_m');

        $count = 0;

        Avocat::select('id_avocat')->chunk(200, function ($avocats) use (&$count) {
            foreach ($avocats as $avocat) {
                $this->recalculerEtSauvegarder($avocat->id_avocat);
                $count++;
            }
        });

        return $count;
    }
}