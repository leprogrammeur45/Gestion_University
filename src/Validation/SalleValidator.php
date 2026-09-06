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
        if (!isset($data['capacite']) || !v::intType()->between(1, 1000)->validate($data['capacite'])) {
            $errors['capacite'][] = 'La capacité doit être un entier compris entre 1 et 1000.';
        }

        // type
        if (!isset($data['type']) || !v::in(self::TYPES_AUTORISES)->validate($data['type'])) {
            $errors['type'][] = 'Le type de salle est invalide.';
        }

        // active
        if (!isset($data['active']) || !v::boolType()->validate($data['active'])) {
            $errors['active'][] = 'Le champ active doit être un booléen.';
        }

        return new ValidationResult(
            empty($errors),
            $errors,
            $data
        );
    }
}
