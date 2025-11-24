<?php 

namespace App\Tests\Unit\Service;

use PHPUnit\Framework\TestCase;
use App\Service\RouteCalculator;
use App\Repository\StationRepository;
use App\Repository\ConnectionRepository;
use App\Entity\Station;
use App\Entity\Connection;
use App\Entity\Route;

class RouteCalculatorTest extends TestCase
{
    private RouteCalculator $routeCalculator;
    private StationRepository $stationRepository;
    private ConnectionRepository $connectionRepository;

    protected function setUp(): void
    {
        // Mock
        $this->stationRepository = $this->createMock(StationRepository::class);
        $this->connectionRepository = $this->createMock(ConnectionRepository::class);

        // Service instance
        $this->routeCalculator = new RouteCalculator(
            $this->stationRepository,
            $this->connectionRepository
        );
    }

    public function testCalculateShortestRouteWithValidStations(): void
    {
        // Mock stations
        $fromStation = new Station();
        $fromStation->setShortName('MX');

        $toStation = new Station();
        $toStation->setShortName('CGE');

        $this->stationRepository->method('findOneBy')
            ->willReturnMap([
                [['shortName' => 'MX'], $fromStation],
                [['shortName' => 'CGE'], $toStation],
            ]);

        // Mock connections
        $connection = new Connection();
        $connection->setParentStation('MX');
        $connection->setChildStation('CGE');
        $connection->setDistance(0.65);
        $connection->setNetworkName('MOB');

        $this->connectionRepository->method('findAll')
            ->willReturn([$connection]);

        // Actions
        $route = $this->routeCalculator->calculateShortestRoute('MX', 'CGE', 'ANA-123');

        // Assert
        $this->assertInstanceOf(Route::class, $route);
        $this->assertEquals('MX', $route->getFromStationId());
        $this->assertEquals('CGE', $route->getToStationId());
        $this->assertEquals('ANA-123', $route->getAnalyticCode());
        $this->assertEquals(0.65, $route->getDistanceKm());
        $this->assertEquals(['MX', 'CGE'], $route->getPath());
    }

    public function testCalculateShortestRouteThrowsExceptionForInvalidFromStation(): void
    {
        // Mock station repository to return null
        $this->stationRepository->method('findOneBy')
            ->willReturn(null);

        // Expectations
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Station 'INVALID' not found");

        // Actions 
        $this->routeCalculator->calculateShortestRoute('INVALID', 'B2', 'CODE_X');
    }

    public function testCalculateShortestRouteThrowsExceptionForInvalidToStation(): void
    {
        // Prepare a valid fromStation
        $fromStation = new Station();
        $fromStation->setShortName('A1');

        // Mock station repository to return fromStation for 'A1' and null for 'INVALID'
        $this->stationRepository->method('findOneBy')
            ->willReturnMap([
                [['shortName' => 'A1'], $fromStation],
                [['shortName' => 'INVALID'], null],
            ]);
        // Expectations
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Station 'INVALID' not found");

        // Actions 
        $this->routeCalculator->calculateShortestRoute('A1', 'INVALID', 'CODE_X');
    }

    public function testCalculateShortestRouteThrowsExceptionWhenNoPathFound(): void
    {
        // Mock stations
        $fromStation = new Station();
        $fromStation->setShortName('MX');

        $toStation = new Station();
        $toStation->setShortName('IO');

        $this->stationRepository->method('findOneBy')
            ->willReturnMap([
                [['shortName' => 'MX'], $fromStation],
                [['shortName' => 'IO'], $toStation],
            ]);

        // No connections = disconnected graph
        $this->connectionRepository->method('findAll')
            ->willReturn([]);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage("No path found between 'MX' and 'IO'");

        $this->routeCalculator->calculateShortestRoute('MX', 'IO', 'ANA-123');
    }
}