--
-- Fichier de MAJ SQL
-- depuis 1.4.0
--

ALTER TABLE `Membres`
 ADD `GameGrade` VARCHAR(50) NOT NULL,
 ADD `pts_architecte` INT(10) NOT NULL,
 ADD `pts_mineur` INT(10) NOT NULL,
 ADD `pts_science` INT(10) NOT NULL,
 ADD `pts_commercant` INT(10) NOT NULL,
 ADD `pts_amiral` INT(10) NOT NULL,
 ADD `pts_guerrier` INT(10) NOT NULL;