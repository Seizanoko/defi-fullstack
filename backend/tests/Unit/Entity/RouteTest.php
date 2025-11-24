<?php

namespace App\Tests\Unit\Entity;

use PHPUnit\Framework\TestCase;
use App\Entity\Route;

class RouteTest extends TestCase
{
    public function testDefaults(): void
    {
        // actions
        $route = new Route();

        // assertions
        $this->assertNull($route->getId());
        $this->assertSame('', $route->getFromStationId());
        $this->assertSame('', $route->getToStationId());
        $this->assertSame('', $route->getAnalyticCode());
        $this->assertSame(0.0, $route->getDistanceKm());
        $this->assertSame([], $route->getPath());
        $this->assertInstanceOf(\DateTimeImmutable::class, $route->getCreatedAt());
    }

    public function testSettersAndGetters(): void
    {
        // given
        $fromStationId = 'A1';
        $toStationId = 'B2';
        $analyticCode = 'CODE_X';
        $distanceKm = 12.7;
        $path = ['A1', 'C3', 'B2'];
        $dt = new \DateTimeImmutable('2025-11-23');

        // actions
        $route = new Route();
        $route->setFromStationId($fromStationId);
        $route->setToStationId($toStationId);
        $route->setAnalyticCode($analyticCode);
        $route->setDistanceKm($distanceKm);
        $route->setPath($path);
        $route->setCreatedAt($dt);

        // assertions
        $this->assertSame($fromStationId, $route->getFromStationId());
        $this->assertSame($toStationId, $route->getToStationId());
        $this->assertSame($analyticCode, $route->getAnalyticCode());
        $this->assertSame($distanceKm, $route->getDistanceKm());
        $this->assertSame($path, $route->getPath());
        $this->assertSame($dt, $route->getCreatedAt());
    }

    public function testDistanceKmCannotBeNegative(): void
    {
        // expectations
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('DistanceKm cannot be negative');

        // actions
        $route = new Route();
        $route->setDistanceKm(-5.0);
    }
}
