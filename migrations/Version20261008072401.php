<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261008072401 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE affectation_creneau (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(30) NOT NULL, date_debut DATE NOT NULL, date_fin DATE DEFAULT NULL, creneau_id INT NOT NULL, utilisateur_id INT NOT NULL, UNIQUE INDEX uniq_affectation_creneau_user_debut (creneau_id, utilisateur_id, date_debut), INDEX IDX_3B69A5E07D0729A9 (creneau_id), INDEX IDX_3B69A5E0FB88E14F (utilisateur_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE annee_scolaire (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(20) NOT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL, cloturee TINYINT DEFAULT 0 NOT NULL, etablissement_id INT NOT NULL, UNIQUE INDEX UNIQ_97150C2BA4D60759 (libelle), INDEX IDX_97150C2BFF631228 (etablissement_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE appel (id INT AUTO_INCREMENT NOT NULL, date_appel DATE NOT NULL, statut VARCHAR(30) DEFAULT \'BROUILLON\' NOT NULL, ouvert_le DATETIME NOT NULL, valide_le DATETIME DEFAULT NULL, verrouille_le DATETIME DEFAULT NULL, cree_par_id INT NOT NULL, valide_par_id INT DEFAULT NULL, creneau_id INT NOT NULL, UNIQUE INDEX uniq_appel_date_creneau (date_appel, creneau_id), INDEX IDX_130D3BDFC29C013 (cree_par_id), INDEX IDX_130D3BD6AF12ED9 (valide_par_id), INDEX IDX_130D3BD7D0729A9 (creneau_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE classe (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(50) NOT NULL, niveau VARCHAR(50) NOT NULL, active TINYINT DEFAULT 1 NOT NULL, etablissement_id INT NOT NULL, annee_scolaire_id INT NOT NULL, UNIQUE INDEX uniq_classe_libelle_annee (libelle, etablissement_id, annee_scolaire_id), INDEX IDX_8F87BF96FF631228 (etablissement_id), INDEX IDX_8F87BF969331C741 (annee_scolaire_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE creneau (id INT AUTO_INCREMENT NOT NULL, jour_semaine INT NOT NULL, heure_debut TIME NOT NULL, heure_fin TIME NOT NULL, matiere VARCHAR(255) NOT NULL, salle VARCHAR(255) NOT NULL, date_debut_validite DATE NOT NULL, date_fin_validite DATE NOT NULL, classe_id INT NOT NULL, annee_scolaire_id INT NOT NULL, INDEX IDX_F9668B5F8F5EA509 (classe_id), INDEX IDX_F9668B5F9331C741 (annee_scolaire_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE eleve (id INT AUTO_INCREMENT NOT NULL, ine VARCHAR(11) DEFAULT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, date_naissance DATE NOT NULL, actif TINYINT DEFAULT 1 NOT NULL, UNIQUE INDEX UNIQ_ECA105F77EE1FA43 (ine), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE etablissement (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, code_uai VARCHAR(20) NOT NULL, adresse VARCHAR(255) NOT NULL, actif TINYINT DEFAULT 1 NOT NULL, UNIQUE INDEX UNIQ_20FD592CDD3C719 (code_uai), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE fichier_justificatif (id INT AUTO_INCREMENT NOT NULL, nom_original VARCHAR(255) NOT NULL, type_mime VARCHAR(100) NOT NULL, taille_octets INT NOT NULL, url_stockage VARCHAR(500) NOT NULL, empreinte_sha256 VARCHAR(64) NOT NULL, ajoute_le DATETIME NOT NULL, justificatif_id INT NOT NULL, UNIQUE INDEX UNIQ_D2FB122878C1C1CB (empreinte_sha256), INDEX IDX_D2FB12284B85A991 (justificatif_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE inscription (id INT AUTO_INCREMENT NOT NULL, date_debut DATE NOT NULL, date_fin DATE DEFAULT NULL, statut VARCHAR(30) DEFAULT \'ACTIVE\' NOT NULL, eleve_id INT NOT NULL, classe_id INT NOT NULL, UNIQUE INDEX uniq_inscription_eleve_classe_debut (eleve_id, classe_id, date_debut), INDEX IDX_5E90F6D6A6CC7B2 (eleve_id), INDEX IDX_5E90F6D68F5EA509 (classe_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE journal_audit (id INT AUTO_INCREMENT NOT NULL, horodate DATETIME NOT NULL, action VARCHAR(30) NOT NULL, entite_type VARCHAR(100) NOT NULL, entite_id INT NOT NULL, adresse_ip VARCHAR(45) DEFAULT NULL, user_agent VARCHAR(255) DEFAULT NULL, details LONGTEXT DEFAULT NULL, utilisateur_id INT NOT NULL, INDEX idx_audit_entite (entite_type, entite_id), INDEX idx_audit_horodate (horodate), INDEX IDX_71C3CC53FB88E14F (utilisateur_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE justificatif (id INT AUTO_INCREMENT NOT NULL, source VARCHAR(30) NOT NULL, statut VARCHAR(30) DEFAULT \'DEPOSE\' NOT NULL, commentaire LONGTEXT DEFAULT NULL, depose_le DATETIME NOT NULL, eleve_id INT NOT NULL, INDEX IDX_90D3C5DCA6CC7B2 (eleve_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE motif (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(50) NOT NULL, libelle VARCHAR(100) NOT NULL, justificatif_requis TINYINT DEFAULT 0 NOT NULL, actif TINYINT DEFAULT 1 NOT NULL, UNIQUE INDEX UNIQ_87D377BB77153098 (code), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE permission (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(50) NOT NULL, libelle VARCHAR(100) NOT NULL, UNIQUE INDEX UNIQ_E04992AA77153098 (code), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE presence (id INT AUTO_INCREMENT NOT NULL, statut VARCHAR(30) DEFAULT \'PRESENT\' NOT NULL, minutes_retard INT DEFAULT 0 NOT NULL, minutes_absence INT DEFAULT 0 NOT NULL, commentaire LONGTEXT DEFAULT NULL, saisi_le DATETIME NOT NULL, appel_id INT NOT NULL, inscription_id INT NOT NULL, UNIQUE INDEX uniq_presence_appel_inscription (appel_id, inscription_id), INDEX IDX_6977C7A5270B0E02 (appel_id), INDEX IDX_6977C7A55DAC5993 (inscription_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE role (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(50) NOT NULL, libelle VARCHAR(100) NOT NULL, UNIQUE INDEX UNIQ_57698A6A77153098 (code), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE role_permission (role_id INT NOT NULL, permission_id INT NOT NULL, INDEX IDX_6F7DF886D60322AC (role_id), INDEX IDX_6F7DF886FED90CCA (permission_id), PRIMARY KEY (role_id, permission_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE traitement_absence (id INT AUTO_INCREMENT NOT NULL, decision VARCHAR(30) DEFAULT \'EN_ATTENTE\' NOT NULL, commentaire LONGTEXT DEFAULT NULL, traite_le DATETIME NOT NULL, est_courant TINYINT DEFAULT 0 NOT NULL, courant_unique SMALLINT DEFAULT NULL, presence_id INT NOT NULL, traite_par_id INT NOT NULL, motif_id INT DEFAULT NULL, UNIQUE INDEX uniq_traitement_courant_presence (presence_id, courant_unique), INDEX IDX_C406E665F328FFC4 (presence_id), INDEX IDX_C406E665167FABE8 (traite_par_id), INDEX IDX_C406E665D0EEB819 (motif_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE traitement_justificatif (traitement_absence_id INT NOT NULL, justificatif_id INT NOT NULL, INDEX IDX_B33AF379C91A3D60 (traitement_absence_id), INDEX IDX_B33AF3794B85A991 (justificatif_id), PRIMARY KEY (traitement_absence_id, justificatif_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, email VARCHAR(180) NOT NULL, mot_de_passe_hash VARCHAR(255) NOT NULL, actif TINYINT DEFAULT 1 NOT NULL, dernier_acces_le DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_1D1C63B3E7927C74 (email), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE utilisateur_etablissement (id INT AUTO_INCREMENT NOT NULL, date_debut DATE NOT NULL, date_fin DATE DEFAULT NULL, utilisateur_id INT NOT NULL, etablissement_id INT NOT NULL, UNIQUE INDEX uniq_user_etab_debut (utilisateur_id, etablissement_id, date_debut), INDEX IDX_42008AEFB88E14F (utilisateur_id), INDEX IDX_42008AEFF631228 (etablissement_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE utilisateur_role (id INT AUTO_INCREMENT NOT NULL, date_debut DATE NOT NULL, date_fin DATE DEFAULT NULL, utilisateur_id INT NOT NULL, role_id INT NOT NULL, UNIQUE INDEX uniq_user_role_debut (utilisateur_id, role_id, date_debut), INDEX IDX_9EE8E650FB88E14F (utilisateur_id), INDEX IDX_9EE8E650D60322AC (role_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE affectation_creneau ADD CONSTRAINT FK_3B69A5E07D0729A9 FOREIGN KEY (creneau_id) REFERENCES creneau (id)');
        $this->addSql('ALTER TABLE affectation_creneau ADD CONSTRAINT FK_3B69A5E0FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE annee_scolaire ADD CONSTRAINT FK_97150C2BFF631228 FOREIGN KEY (etablissement_id) REFERENCES etablissement (id)');
        $this->addSql('ALTER TABLE appel ADD CONSTRAINT FK_130D3BDFC29C013 FOREIGN KEY (cree_par_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE appel ADD CONSTRAINT FK_130D3BD6AF12ED9 FOREIGN KEY (valide_par_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE appel ADD CONSTRAINT FK_130D3BD7D0729A9 FOREIGN KEY (creneau_id) REFERENCES creneau (id)');
        $this->addSql('ALTER TABLE classe ADD CONSTRAINT FK_8F87BF96FF631228 FOREIGN KEY (etablissement_id) REFERENCES etablissement (id)');
        $this->addSql('ALTER TABLE classe ADD CONSTRAINT FK_8F87BF969331C741 FOREIGN KEY (annee_scolaire_id) REFERENCES annee_scolaire (id)');
        $this->addSql('ALTER TABLE creneau ADD CONSTRAINT FK_F9668B5F8F5EA509 FOREIGN KEY (classe_id) REFERENCES classe (id)');
        $this->addSql('ALTER TABLE creneau ADD CONSTRAINT FK_F9668B5F9331C741 FOREIGN KEY (annee_scolaire_id) REFERENCES annee_scolaire (id)');
        $this->addSql('ALTER TABLE fichier_justificatif ADD CONSTRAINT FK_D2FB12284B85A991 FOREIGN KEY (justificatif_id) REFERENCES justificatif (id)');
        $this->addSql('ALTER TABLE inscription ADD CONSTRAINT FK_5E90F6D6A6CC7B2 FOREIGN KEY (eleve_id) REFERENCES eleve (id)');
        $this->addSql('ALTER TABLE inscription ADD CONSTRAINT FK_5E90F6D68F5EA509 FOREIGN KEY (classe_id) REFERENCES classe (id)');
        $this->addSql('ALTER TABLE journal_audit ADD CONSTRAINT FK_71C3CC53FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE justificatif ADD CONSTRAINT FK_90D3C5DCA6CC7B2 FOREIGN KEY (eleve_id) REFERENCES eleve (id)');
        $this->addSql('ALTER TABLE presence ADD CONSTRAINT FK_6977C7A5270B0E02 FOREIGN KEY (appel_id) REFERENCES appel (id)');
        $this->addSql('ALTER TABLE presence ADD CONSTRAINT FK_6977C7A55DAC5993 FOREIGN KEY (inscription_id) REFERENCES inscription (id)');
        $this->addSql('ALTER TABLE role_permission ADD CONSTRAINT FK_6F7DF886D60322AC FOREIGN KEY (role_id) REFERENCES role (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE role_permission ADD CONSTRAINT FK_6F7DF886FED90CCA FOREIGN KEY (permission_id) REFERENCES permission (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE traitement_absence ADD CONSTRAINT FK_C406E665F328FFC4 FOREIGN KEY (presence_id) REFERENCES presence (id)');
        $this->addSql('ALTER TABLE traitement_absence ADD CONSTRAINT FK_C406E665167FABE8 FOREIGN KEY (traite_par_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE traitement_absence ADD CONSTRAINT FK_C406E665D0EEB819 FOREIGN KEY (motif_id) REFERENCES motif (id)');
        $this->addSql('ALTER TABLE traitement_justificatif ADD CONSTRAINT FK_B33AF379C91A3D60 FOREIGN KEY (traitement_absence_id) REFERENCES traitement_absence (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE traitement_justificatif ADD CONSTRAINT FK_B33AF3794B85A991 FOREIGN KEY (justificatif_id) REFERENCES justificatif (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE utilisateur_etablissement ADD CONSTRAINT FK_42008AEFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE utilisateur_etablissement ADD CONSTRAINT FK_42008AEFF631228 FOREIGN KEY (etablissement_id) REFERENCES etablissement (id)');
        $this->addSql('ALTER TABLE utilisateur_role ADD CONSTRAINT FK_9EE8E650FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE utilisateur_role ADD CONSTRAINT FK_9EE8E650D60322AC FOREIGN KEY (role_id) REFERENCES role (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE affectation_creneau DROP FOREIGN KEY FK_3B69A5E07D0729A9');
        $this->addSql('ALTER TABLE affectation_creneau DROP FOREIGN KEY FK_3B69A5E0FB88E14F');
        $this->addSql('ALTER TABLE annee_scolaire DROP FOREIGN KEY FK_97150C2BFF631228');
        $this->addSql('ALTER TABLE appel DROP FOREIGN KEY FK_130D3BDFC29C013');
        $this->addSql('ALTER TABLE appel DROP FOREIGN KEY FK_130D3BD6AF12ED9');
        $this->addSql('ALTER TABLE appel DROP FOREIGN KEY FK_130D3BD7D0729A9');
        $this->addSql('ALTER TABLE classe DROP FOREIGN KEY FK_8F87BF96FF631228');
        $this->addSql('ALTER TABLE classe DROP FOREIGN KEY FK_8F87BF969331C741');
        $this->addSql('ALTER TABLE creneau DROP FOREIGN KEY FK_F9668B5F8F5EA509');
        $this->addSql('ALTER TABLE creneau DROP FOREIGN KEY FK_F9668B5F9331C741');
        $this->addSql('ALTER TABLE fichier_justificatif DROP FOREIGN KEY FK_D2FB12284B85A991');
        $this->addSql('ALTER TABLE inscription DROP FOREIGN KEY FK_5E90F6D6A6CC7B2');
        $this->addSql('ALTER TABLE inscription DROP FOREIGN KEY FK_5E90F6D68F5EA509');
        $this->addSql('ALTER TABLE journal_audit DROP FOREIGN KEY FK_71C3CC53FB88E14F');
        $this->addSql('ALTER TABLE justificatif DROP FOREIGN KEY FK_90D3C5DCA6CC7B2');
        $this->addSql('ALTER TABLE presence DROP FOREIGN KEY FK_6977C7A5270B0E02');
        $this->addSql('ALTER TABLE presence DROP FOREIGN KEY FK_6977C7A55DAC5993');
        $this->addSql('ALTER TABLE role_permission DROP FOREIGN KEY FK_6F7DF886D60322AC');
        $this->addSql('ALTER TABLE role_permission DROP FOREIGN KEY FK_6F7DF886FED90CCA');
        $this->addSql('ALTER TABLE traitement_absence DROP FOREIGN KEY FK_C406E665F328FFC4');
        $this->addSql('ALTER TABLE traitement_absence DROP FOREIGN KEY FK_C406E665167FABE8');
        $this->addSql('ALTER TABLE traitement_absence DROP FOREIGN KEY FK_C406E665D0EEB819');
        $this->addSql('ALTER TABLE traitement_justificatif DROP FOREIGN KEY FK_B33AF379C91A3D60');
        $this->addSql('ALTER TABLE traitement_justificatif DROP FOREIGN KEY FK_B33AF3794B85A991');
        $this->addSql('ALTER TABLE utilisateur_etablissement DROP FOREIGN KEY FK_42008AEFB88E14F');
        $this->addSql('ALTER TABLE utilisateur_etablissement DROP FOREIGN KEY FK_42008AEFF631228');
        $this->addSql('ALTER TABLE utilisateur_role DROP FOREIGN KEY FK_9EE8E650FB88E14F');
        $this->addSql('ALTER TABLE utilisateur_role DROP FOREIGN KEY FK_9EE8E650D60322AC');
        $this->addSql('DROP TABLE affectation_creneau');
        $this->addSql('DROP TABLE annee_scolaire');
        $this->addSql('DROP TABLE appel');
        $this->addSql('DROP TABLE classe');
        $this->addSql('DROP TABLE creneau');
        $this->addSql('DROP TABLE eleve');
        $this->addSql('DROP TABLE etablissement');
        $this->addSql('DROP TABLE fichier_justificatif');
        $this->addSql('DROP TABLE inscription');
        $this->addSql('DROP TABLE journal_audit');
        $this->addSql('DROP TABLE justificatif');
        $this->addSql('DROP TABLE motif');
        $this->addSql('DROP TABLE permission');
        $this->addSql('DROP TABLE presence');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE role_permission');
        $this->addSql('DROP TABLE traitement_absence');
        $this->addSql('DROP TABLE traitement_justificatif');
        $this->addSql('DROP TABLE utilisateur');
        $this->addSql('DROP TABLE utilisateur_etablissement');
        $this->addSql('DROP TABLE utilisateur_role');
    }
}
