<?php

namespace Tests\Validation;

use App\Validation\ReservationValidator;
use PHPUnit\Framework\TestCase;

class ReservationValidatorTest extends TestCase
{
    public function testReservationValide(): void
    {
        $validator = new ReservationValidator();

        $data = [
            'salle_id' => '1',
            'responsable' => 'Mamadou Diouf',
            'email' => 'mamadou@example.com',
            'motif' => 'Réunion pédagogique',
            'date_debut' => '2026-09-10T10:00',
            'date_fin' => '2026-09-10T12:00',
        ];

        $result = $validator->validate($data);

        $this->assertTrue($result->isValid());
    }

    public function testReservationInvalide(): void
    {
        $validator = new ReservationValidator();

        $data = [
            'salle_id' => '-1',
            'responsable' => 'Mamadou Diouf',
            'email' => 'mamadou@example.com',
            'motif' => 'Réunion pédagogique',
            'date_debut' => '2026-09-10T10:00',
            'date_fin' => '2026-09-10T12:00',
        ];

        $result = $validator->validate($data);

        $this->assertFalse($result->isValid());
    }

    public function testRefuseUneAdresseEmailInvalide(): void
    {
        $result = (new ReservationValidator())->validate([
            'salle_id' => '1',
            'responsable' => 'Mamadou Diouf',
            'email' => 'adresse-invalide',
            'motif' => 'Réunion pédagogique',
            'date_debut' => '2026-09-10T10:00',
            'date_fin' => '2026-09-10T12:00',
        ]);

        $this->assertFalse($result->isValid());
        $this->assertArrayHasKey('email', $result->errors());
    }

    public function testRefuseUnResponsableVide(): void
    {
        $result = (new ReservationValidator())->validate([
            'salle_id' => '1',
            'responsable' => '',
            'email' => 'mamadou@example.com',
            'motif' => 'Réunion pédagogique',
            'date_debut' => '2026-09-10T10:00',
            'date_fin' => '2026-09-10T12:00',
        ]);

        $this->assertFalse($result->isValid());
        $this->assertArrayHasKey('responsable', $result->errors());
    }

    public function testRefuseUneDateIncorrecte(): void
    {
        $result = (new ReservationValidator())->validate([
            'salle_id' => '1',
            'responsable' => 'Mamadou Diouf',
            'email' => 'mamadou@example.com',
            'motif' => 'Réunion pédagogique',
            'date_debut' => 'date-invalide',
            'date_fin' => '2026-09-10T12:00',
        ]);

        $this->assertFalse($result->isValid());
        $this->assertArrayHasKey('date_debut', $result->errors());
    }
}