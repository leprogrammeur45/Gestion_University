<?php

namespace Tests\Validation;

use App\Validation\SalleValidator;
use PHPUnit\Framework\TestCase;

class SalleValidatorTest extends TestCase
{
    public function testAccepteLesValeursEnvoyeesParUnFormulaireHtml(): void
    {
        $result = (new SalleValidator())->validate([
            'nom' => 'Salle B12',
            'batiment' => 'Bâtiment B',
            'capacite' => '40',
            'type' => 'cours',
            'active' => '1',
        ]);

        $this->assertTrue($result->isValid());
    }

    public function testRefuseUneCapaciteInvalide(): void
    {
        $result = (new SalleValidator())->validate([
            'nom' => 'Salle B12',
            'batiment' => 'Bâtiment B',
            'capacite' => '0',
            'type' => 'cours',
            'active' => '1',
        ]);

        $this->assertFalse($result->isValid());
        $this->assertArrayHasKey('capacite', $result->errors());
    }
}