<?php

namespace App\Entity;

use App\Repository\AffectationCreneauRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity(repositoryClass: AffectationCreneauRepository::class)]
#[ORM\Table(name: 'affectation_creneau')]
#[ORM\UniqueConstraint(name: 'uniq_affectation_creneau_user_debut', columns: ['creneau_id', 'utilisateur_id', 'date_debut'])]
#[UniqueEntity(fields: ['creneau', 'utilisateur', 'dateDebut'], errorPath: 'creneau', message: 'Cette affectation existe déjà à cette date.')]
class AffectationCreneau
{
    public const TYPE_TITULAIRE = 'TITULAIRE';
    public const TYPE_REMPLACANT = 'REMPLACANT';
    public const TYPE_CO_INTERVENANT = 'CO_INTERVENANT';
    public const TYPES = [self::TYPE_TITULAIRE, self::TYPE_REMPLACANT, self::TYPE_CO_INTERVENANT];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 30)]
    #[Assert\Choice(choices: self::TYPES)]
    private ?string $type = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    #[Assert\NotNull]
    private ?\DateTimeImmutable $dateDebut = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $dateFin = null;

    #[ORM\ManyToOne(targetEntity: Creneau::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull]
    private ?Creneau $creneau = null;

    #[ORM\ManyToOne(targetEntity: Utilisateur::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull]
    private ?Utilisateur $utilisateur = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeImmutable
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeImmutable $dateDebut): static
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeImmutable
    {
        return $this->dateFin;
    }

    public function setDateFin(?\DateTimeImmutable $dateFin): static
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getCreneau(): ?Creneau
    {
        return $this->creneau;
    }

    public function setCreneau(Creneau $creneau): static
    {
        $this->creneau = $creneau;

        return $this;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(Utilisateur $utilisateur): static
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    #[Assert\Callback]
    public function validateCoherence(ExecutionContextInterface $context): void
    {
        if ($this->dateDebut !== null && $this->dateFin !== null && $this->dateFin < $this->dateDebut) {
            $context->buildViolation('La date de fin doit être postérieure ou égale à la date de début.')->atPath('dateFin')->addViolation();
        }
    }
}
