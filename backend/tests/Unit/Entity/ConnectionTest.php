<?php

namespace App\Tests\Unit\Entity;

use PHPUnit\Framework\TestCase;
use App\Entity\Connection;

class ConnectionTest extends TestCase
{
    public function testDefaults(): void
    {
        // actions
        $connection = new Connection();

        // assertions
        $this->assertNull($connection->getId());
        $this->assertSame('', $connection->getParentStation());
        $this->assertSame('', $connection->getChildStation());
        $this->assertSame(0.0, $connection->getDistance());
        $this->assertSame('', $connection->getNetworkName());
    }

    public function testSettersAndGetters(): void
    {
        // given
        $parentStation = 'ST_A';
        $childStation = 'ST_B';
        $distance = 5.25;
        $networkName = 'MOB';

        // actions
        $connection = new Connection();
        $connection->setParentStation($parentStation);
        $connection->setChildStation($childStation);
        $connection->setDistance($distance);
        $connection->setNetworkName($networkName);

        // assertions
        $this->assertSame($parentStation, $connection->getParentStation());
        $this->assertSame($childStation, $connection->getChildStation());
        $this->assertSame($distance, $connection->getDistance());
        $this->assertSame($networkName, $connection->getNetworkName());
    }

    public function testDistanceCannotBeNegative(): void
    {
        // expectations
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Distance cannot be negative');

        // actions
        $connection = new Connection();
        $connection->setDistance(-10);
    }
}
