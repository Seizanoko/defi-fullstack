<?php
// src/Controller/StatsController.php

namespace App\Controller;

use App\Repository\RouteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class StatsController extends AbstractController
{
    public function __construct(
        private RouteRepository $routeRepository
    ) {}

    #[Route('/api/v1/stats/distances', name: 'api_stats_distances', methods: ['GET'])]
    public function getDistances(Request $request): JsonResponse
    {
        // 1. Extract query parameters
        $from = $request->query->get('from');
        $to = $request->query->get('to');
        $groupBy = $request->query->get('groupBy', 'none');

        // 2. Validate parameters
        $validationError = $this->validateParameters($from, $to, $groupBy);
        if ($validationError) {
            return $this->json($validationError, Response::HTTP_BAD_REQUEST);
        }

        // 3. Parse dates
        $fromDate = $from ? new \DateTimeImmutable($from) : null;
        $toDate = $to ? new \DateTimeImmutable($to . ' 23:59:59') : null;

        // 4. Validate date range
        if ($fromDate && $toDate && $fromDate > $toDate) {
            return $this->json([
                'message' => 'Parameter "from" must be before or equal to "to"'
            ], Response::HTTP_BAD_REQUEST);
        }

        // 5. Get statistics from repository
        try {
            $items = $this->routeRepository->getDistanceStatistics(
                $fromDate,
                $toDate,
                $groupBy
            );

            // 6. Return response
            return $this->json([
                'from' => $from,
                'to' => $to,
                'groupBy' => $groupBy,
                'items' => $items
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            return $this->json([
                'message' => 'Error calculating statistics',
                'details' => [$e->getMessage()]
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @return array{
     *     message: string,
     *     details?: array<string>
     * }|null
     */
    private function validateParameters(?string $from, ?string $to, string $groupBy): ?array
    {
        // Validate groupBy
        $validGroupBy = ['none', 'day', 'month', 'year'];
        if (!in_array($groupBy, $validGroupBy)) {
            return [
                'message' => 'Invalid groupBy parameter',
                'details' => ['groupBy must be one of: ' . implode(', ', $validGroupBy)]
            ];
        }

        // Validate date formats
        if ($from && !$this->isValidDate($from)) {
            return [
                'message' => 'Invalid date format for "from"',
                'details' => ['Expected format: YYYY-MM-DD']
            ];
        }

        if ($to && !$this->isValidDate($to)) {
            return [
                'message' => 'Invalid date format for "to"',
                'details' => ['Expected format: YYYY-MM-DD']
            ];
        }

        if (!$from && $to) {
            return [
                'message' => 'Parameter "from" is required when "to" is provided',
            ];
        }

        if (!$to && $from) {
            return [
                'message' => 'Parameter "to" is required when "from" is provided'
            ];
        }

        return null;
    }

    private function isValidDate(string $date): bool
    {
        $d = \DateTimeImmutable::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }
}