WITH nu AS (
    INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, telephone, role, statut, region, created_at, updated_at)
    VALUES ('Admin', 'Admin', 'admin@gmail.com', '$2y$12$Ebf.BTGVJw5OxcIQXsMkB.WUTjWsf/pBpBTstSdDJt8I5mzwJPxBW', NULL, 'admin', 'actif', NULL, NOW(), NOW())
    RETURNING id_utilisateur
)
INSERT INTO admins (id_utilisateur)
SELECT id_utilisateur FROM nu;