<?php

namespace App\Validation;

use Respect\Validation\Validator as v;

class ReservationValidator implements ValidatorInterface
{
    /** Vérifie la forme des données saisies pour une réservation. */
    public function validate(array $data): ValidationResult
    {
        $errors = [];

        // salle_id
        if (
            !isset($data['salle_id']) ||
            !v::stringType()->digit()->notEmpty()->validate($data['salle_id']) ||
            (int) $data['salle_id'] < 1
        ) {
            $errors['salle_id'][] =
                'L\'identifiant de la salle doit être un entier positif.';
        }

        // responsable
        if (
            !isset($data['responsable']) ||
            !v::stringType()->length(2, 120)->validate($data['responsable'])
        ) {
            $errors['responsable'][] =
                'Le responsable doit contenir entre 2 et 120 caractères.';
        }

        // email
        if (
            !isset($data['email']) ||
            !v::email()->validate($data['email'])
        ) {
            $errors['email'][] =
                'L\'adresse email est invalide.';
        }

        // motif
        if (
            !isset($data['motif']) ||
            !v::stringType()->length(5, 255)->validate($data['motif'])
        ) {
            $errors['motif'][] =
                'Le motif doit contenir entre 5 et 255 caractères.';
        }

        // date_debut
        if (
            !isset($data['date_debut']) ||
            !v::dateTime('Y-m-d\TH:i')->validate($data['date_debut'])
        ) {
            $errors['date_debut'][] =
                'La date de début est invalide.';
        }

        // date_fin
        if (
            !isset($data['date_fin']) ||
            !v::dateTime('Y-m-d\TH:i')->validate($data['date_fin'])
        ) {
            $errors['date_fin'][] =
                'La date de fin est invalide.';
        }

        return new ValidationResult(
            empty($errors),
            $errors,
            $data
        );
    }
}