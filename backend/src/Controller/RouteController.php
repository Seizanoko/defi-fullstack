<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\RouteCalculator;

#[IsGranted('ROLE_USER')]
final class RouteController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private RouteCalculator $routeCalculator,
    ) {}

    #[Route('/api/v1/routes', methods: ['POST'])]
    public function calculateRoute(Request $request): JsonResponse
    {
        // Decode the JSON request body
        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return $this->json([
                'message' => 'Invalid JSON',
            ], Response::HTTP_BAD_REQUEST);
        }

        // Validate required fields
        $requiredFields = ['fromStationId', 'toStationId', 'analyticCode'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                return $this->json([
                    'message' => "Missing required field: $field",
                ], Response::HTTP_BAD_REQUEST);
            }
        }

        try {
            // Calculate the shortest route
            $route = $this->routeCalculator->calculateShortestRoute(
                $data['fromStationId'],
                $data['toStationId'],
                $data['analyticCode']
            );

            // Persist the route entity to the database
            $this->entityManager->persist($route);
            $this->entityManager->flush();

            // Prepare response
            $response = [
                'id' => $route->getId(),
                'fromStationId' => $route->getFromStationId(),
                'toStationId' => $route->getToStationId(),
                'analyticCode' => $route->getAnalyticCode(),
                'distanceKm' => $route->getDistanceKm(),
                'path' => $route->getPath(),
                'createdAt' => $route->getCreatedAt()->format('Y-m-d'),
            ];

            return $this->json($response, Response::HTTP_CREATED);

        } catch (\InvalidArgumentException $e) {
            // Station not found or invalid input
            return $this->json([
                'message' => $e->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
            
        } catch (\RuntimeException $e) {
            // No path found (network not connected)
            return $this->json([
                'message' => $e->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

    }
}
