<?php

namespace App\Services;

use App\Models\Paradero;
use Illuminate\Support\Collection;

class GrafoRutas
{
    private array $grafo = [];
    private array $nodos = [];

    public function construir(): void
    {
        $paraderos = Paradero::with('ruta')->orderBy('ruta_id')->orderBy('orden')->get();
        $this->grafo = [];
        $this->nodos = [];

        foreach ($paraderos as $p) {
            $id = $p->id;
            $this->nodos[$id] = $p;
            if (!isset($this->grafo[$id])) {
                $this->grafo[$id] = [];
            }
        }

        foreach ($paraderos as $p) {
            $siguiente = $paraderos->where('ruta_id', $p->ruta_id)->where('orden', $p->orden + 1)->first();
            if ($siguiente) {
                $dist = $this->haversine($p->latitud, $p->longitud, $siguiente->latitud, $siguiente->longitud);
                $this->grafo[$p->id][] = ['nodo' => $siguiente->id, 'dist' => $dist];
                $this->grafo[$siguiente->id][] = ['nodo' => $p->id, 'dist' => $dist];
            }
        }

        // Conectar paraderos de rutas diferentes si están cerca (transferencia)
        $ids = array_keys($this->nodos);
        $umbral = 0.5; // km — caminata de ~5 min
        $penalidadTransferencia = 2.0; // km — penalidad por hacer transbordo
        for ($i = 0; $i < count($ids); $i++) {
            $a = $this->nodos[$ids[$i]];
            for ($j = $i + 1; $j < count($ids); $j++) {
                $b = $this->nodos[$ids[$j]];
                if ($a->ruta_id === $b->ruta_id) continue;
                $dist = $this->haversine($a->latitud, $a->longitud, $b->latitud, $b->longitud);
                if ($dist <= $umbral) {
                    $peso = $dist + $penalidadTransferencia;
                    $this->grafo[$a->id][] = ['nodo' => $b->id, 'dist' => $peso];
                    $this->grafo[$b->id][] = ['nodo' => $a->id, 'dist' => $peso];
                }
            }
        }
    }

    public function rutaMasCorta(int $origenId, int $destinoId): ?array
    {
        if (!isset($this->nodos[$origenId]) || !isset($this->nodos[$destinoId])) {
            return null;
        }

        $origen = $this->nodos[$origenId];
        $destino = $this->nodos[$destinoId];

        // Si origen y destino están en la misma ruta, ruta directa (no pasar por todos)
        if ($origen->ruta_id === $destino->ruta_id) {
            $path = [$origen, $destino];
            $totalKm = $this->haversine($origen->latitud, $origen->longitud, $destino->latitud, $destino->longitud);

            return [
                'paraderos' => $path,
                'distancia_km' => round($totalKm, 2),
                'tiempo_min' => round($totalKm * 3),
                'nodos_recorridos' => 2,
            ];
        }

        // Dijkstra completo solo para rutas entre rutas diferentes
        $dist = [];
        $prev = [];
        $pq = new \SplPriorityQueue();
        $visitados = [];

        foreach ($this->nodos as $id => $nodo) {
            $dist[$id] = INF;
            $prev[$id] = null;
        }
        $dist[$origenId] = 0;
        $pq->insert($origenId, 0);

        while (!$pq->isEmpty()) {
            $u = $pq->extract();
            if (isset($visitados[$u])) continue;
            $visitados[$u] = true;

            if ($u === $destinoId) break;

            foreach ($this->grafo[$u] ?? [] as $vecino) {
                $v = $vecino['nodo'];
                $peso = $vecino['dist'];
                $alt = $dist[$u] + $peso;
                if ($alt < $dist[$v]) {
                    $dist[$v] = $alt;
                    $prev[$v] = $u;
                    $pq->insert($v, -$alt);
                }
            }
        }

        if ($prev[$destinoId] === null && $origenId !== $destinoId) {
            return null;
        }

        $path = [];
        $actual = $destinoId;
        while ($actual !== null) {
            array_unshift($path, $this->nodos[$actual]);
            $actual = $prev[$actual];
        }

        $totalKm = 0;
        for ($i = 0; $i < count($path) - 1; $i++) {
            $totalKm += $this->haversine(
                $path[$i]->latitud, $path[$i]->longitud,
                $path[$i + 1]->latitud, $path[$i + 1]->longitud
            );
        }

        return [
            'paraderos' => $path,
            'distancia_km' => round($totalKm, 2),
            'tiempo_min' => round($totalKm * 3),
            'nodos_recorridos' => count($path),
        ];
    }

    private function haversine($lat1, $lng1, $lat2, $lng2): float
    {
        $R = 6371;
        $dLat = deg2rad((float)$lat2 - (float)$lat1);
        $dLng = deg2rad((float)$lng2 - (float)$lng1);
        $a = sin($dLat / 2) ** 2 + cos(deg2rad((float)$lat1)) * cos(deg2rad((float)$lat2)) * sin($dLng / 2) ** 2;
        return $R * 2 * atan2(sqrt($a), sqrt(1 - $a));
    }
}
