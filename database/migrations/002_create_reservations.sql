CREATE TABLE reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,

    salle_id INT NOT NULL,

    responsable VARCHAR(150) NOT NULL,

    email VARCHAR(255) NOT NULL,

    motif VARCHAR(255) NOT NULL,

    date_debut DATETIME NOT NULL,

    date_fin DATETIME NOT NULL,

    statut ENUM(
        'confirmee',
        'annulee'
    ) NOT NULL DEFAULT 'confirmee',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_reservations_salle
        FOREIGN KEY (salle_id)
        REFERENCES salles(id)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
);
