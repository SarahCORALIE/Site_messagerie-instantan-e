 -- 1 -- Creation d'une BDD : 'dialogue'

		CREATE DATABASE dialogue;
		USE dialogue;

	-- 2 -- Création d'une table : 'commentaire' (id_commentaire, pseudo, message, date_enregistrement)

		CREATE TABLE message(
			id_message INT(3) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			id_emmeteur VARCHAR(20) NOT NULL,
			message TEXT NOT NULL,
			date_enregistrement DATETIME NOT NULL
		) ENGINE=InnoDB;

        CREATE TABLE membre(
			id_commentaire INT(3) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			pseudo VARCHAR(20) NOT NULL,
			message TEXT NOT NULL,
			date_enregistrement DATETIME NOT NULL
		) ENGINE=InnoDB;