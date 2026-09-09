<?php

use PHPUnit\Framework\TestCase;

class ReservationFormTest extends TestCase
{
    public function testUneErreurEstAfficheePresDuChampConcerne(): void
    {
        $errors = [
            'email' => [
                'L’adresse email est invalide.'
            ]
        ];

        $data = [
            'salle_id' => '1',
            'responsable' => 'Mamadou',
            'email' => 'email-invalide',
            'motif' => 'Cours',
            'date_debut' => '2026-09-15T10:00',
            'date_fin' => '2026-09-15T12:00',
        ];

        $salles = [];

        ob_start();

        require __DIR__ . '/../../../templates/reservation/form.php';

        $html = ob_get_clean();

        $this->assertStringContainsString(
            'L’adresse email est invalide.',
            $html
        );

        $this->assertMatchesRegularExpression(
            '/<input[^>]*name="email"[^>]*>.*?<div class="field-error">.*?L’adresse email est invalide\./s',
            $html
        );
    }
}
