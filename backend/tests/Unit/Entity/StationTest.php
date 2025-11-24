<?php

namespace App\Tests\Unit\Entity;

use PHPUnit\Framework\TestCase;

use App\Entity\Station;

class StationTest extends TestCase
{
    public function testDefaults(): void
    {
        // actions
        $station = new Station();

        // assertions
        $this->assertNull($station->getId(), 'id is null until persisted');
        $this->assertSame('', $station->getShortName(), 'no names set by default');
        $this->assertSame('', $station->getLongName(), 'no names set by default');
    }

    public function testSettersAndGetters(): void
    {
        // given
        $shortName = 'TSTA';
        $longName = 'Test Station';
        
        // actions
        $station = new Station();
        $station->setShortName($shortName);
        $station->setLongName($longName);

        // assertions
        $this->assertSame($shortName, $station->getShortName());
        $this->assertSame($longName, $station->getLongName());
    }
}
