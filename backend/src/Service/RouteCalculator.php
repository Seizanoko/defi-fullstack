<?php

namespace App\Service;

use App\Repository\ConnectionRepository;
use App\Repository\StationRepository;
use App\Entity\Route;

class RouteCalculator
{
    public function __construct(
        private StationRepository $stationRepository,
        private ConnectionRepository $connectionRepository
    ) {}

    public function calculateShortestRoute(string $fromStationId, string $toStationId, string $analyticCode): Route
    {
        // Validate stations exist
        $fromStation = $this->stationRepository->findOneBy(['shortName' => $fromStationId]);
        if (!$fromStation) {
            throw new \InvalidArgumentException("Station '$fromStationId' not found");
        }

        $toStation = $this->stationRepository->findOneBy(['shortName' => $toStationId]);
        if (!$toStation) {
            throw new \InvalidArgumentException("Station '$toStationId' not found");
        }

        // Compute shortest path using Dijkstra's algorithm
        $result = $this->dijkstra($fromStationId, $toStationId);

        if (!$result) {
            throw new \RuntimeException("No path found between '$fromStationId' and '$toStationId'");
        }

        // Create Route entity
        $route = new Route();
        $route->setFromStationId($fromStationId);
        $route->setToStationId($toStationId);
        $route->setAnalyticCode($analyticCode);
        $route->setDistanceKm($result['distance']);
        $route->setPath($result['path']);
        $route->setCreatedAt(new \DateTimeImmutable());

        return $route;

    }

    private function dijkstra(string $start, string $end): ?array
    {
        // Implementation of Dijkstra's algorithm to find the shortest path
        // between $start and $end stations using connections from the database.

        // Load all connections
        $connections = $this->connectionRepository->findAll();

        // Build graph list from connections (We assume bidirectional connections)
        $graph = [];
        foreach ($connections as $connection) {
            $parent = $connection->getParentStation();
            $child = $connection->getChildStation();
            $distance = $connection->getDistance();

            // Add forward connection
            if (!isset($graph[$parent])) {
                $graph[$parent] = [];
            }
            $graph[$parent][$child] = $distance;

            // Add backward connection
            if (!isset($graph[$child])) {
                $graph[$child] = [];
            }
            $graph[$child][$parent] = $distance;
        }

        // Dijkstra's algorithm
        $distances = [$start => 0];
        $previous = [];
        $queue = new \SplPriorityQueue();
        $queue->insert($start, 0);
        $visited = [];

        while (!$queue->isEmpty()) {
            // Get station with smallest distance
            $currentStationId = $queue->extract();

            // If already visited, skip
            if (isset($visited[$currentStationId])) {
                continue;
            }
            $visited[$currentStationId] = true;

            // If end station is reached
            if ($currentStationId === $end) {
                // Reconstruct path
                $path = [];
                while (isset($previous[$currentStationId])) {
                    array_unshift($path, $currentStationId);
                    $currentStationId = $previous[$currentStationId];
                }
                array_unshift($path, $start);

                return [
                    'distance' => $distances[$end],
                    'path' => $path,
                ];
            }

            // If no neighbors, skip
            if (!isset($graph[$currentStationId])) {
                continue;
            }

            // Explore neighbors
            foreach ($graph[$currentStationId] as $neighbor => $distance) {
                if (isset($visited[$neighbor])) {
                    continue;
                }

                // Calculate alternative distance
                $alt = $distances[$currentStationId] + $distance;

                // If new distance is shorter, update
                if (!isset($distances[$neighbor]) || $alt < $distances[$neighbor]) {
                    $distances[$neighbor] = $alt;
                    $previous[$neighbor] = $currentStationId;
                    $queue->insert($neighbor, -$alt);
                }
            }
            
        }

        // No path found
        return null;
    }
}