<?php

namespace App\Validation;

use Respect\Validation\Validator as v;

class SalleValidator implements ValidatorInterface
{
    private const TYPES_AUTORISES = [
        'cours',
        'informatique',
        'laboratoire',
        'amphitheatre',
        'reunion',
    ];

    /** Vérifie les données saisies pour une salle. */
    public function validate(array $data): ValidationResult
    {
        $errors = [];

        // nom
        if (!isset($data['nom']) || !v::stringType()->notEmpty()->length(2, 100)->validate($data['nom'])) {
            $errors['nom'][] = 'Le nom est obligatoire et doit contenir entre 2 et 100 caractères.';
        }

        // batiment
        if (!isset($data['batiment']) || !v::stringType()->notEmpty()->length(2, 100)->validate($data['batiment'])) {
            $errors['batiment'][] = 'Le bâtiment est obligatoire et doit contenir entre 2 et 100 caractères.';
        }

        // capacite
        $capaciteValide = isset($data['capacite'])
            && filter_var($data['capacite'], FILTER_VALIDATE_INT) !== false
            && (int) $data['capacite'] >= 1
            && (int) $data['capacite'] <= 1000;

        if (!$capaciteValide) {
            $errors['capacite'][] = 'La capacité doit être un entier compris entre 1 et 1000.';
        }

        // type
        if (!isset($data['type']) || !v::in(self::TYPES_AUTORISES)->validate($data['type'])) {
            $errors['type'][] = 'Le type de salle est invalide.';
        }

        // active
        $activeValide = is_bool($data['active'] ?? null)
            || in_array($data['active'] ?? null, ['0', '1', 0, 1], true);

        if (!$activeValide) {
            $errors['active'][] = 'Le champ active doit être un booléen.';
        }

        return new ValidationResult(
            empty($errors),
            $errors,
            $data
        );
    }
}
