CREATE TABLE salles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    batiment VARCHAR(100) NOT NULL,
    capacite INT NOT NULL,
    type ENUM(
        'cours',
        'informatique',
        'laboratoire',
        'amphitheatre',
        'reunion'
    ) NOT NULL,
    active BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP
);
