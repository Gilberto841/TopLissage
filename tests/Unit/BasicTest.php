<?php

namespace App\Tests\Unit;

use App\Entity\HairSalon;
use App\Entity\Professional;
use PHPUnit\Framework\TestCase;

class BasicTest extends TestCase
{
    public function testSomething(): void
    {
        $hairSalon = new HairSalon();
        $professional = new Professional();
        
        $hairSalon->setProfessional($professional);
        
        $this->assertEquals($professional, $hairSalon->getProfessional());
    }
}
