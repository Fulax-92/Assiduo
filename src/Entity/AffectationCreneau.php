<?php

namespace App\Entity;

use App\Enum\TypeAffectation;
use App\Repository\AffectationCreneauRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AffectationCreneauRepository::class)]
class AffectationCreneau
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotNull]
    #[ORM\Column(enumType: TypeAffectation::class, length: 20)]
    private ?TypeAffectation $type = null;

    #[Assert\NotNull]
    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $dateDebut = null;

    #[Assert\GreaterThanOrEqual(propertyPath: 'dateDebut', message: 'La date de fin doit être postérieure ou égale à la date de début.')]
    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $dateFin = null;

    #[Assert\NotNull]
    #[ORM\ManyToOne(targetEntity: Creneau::class, inversedBy: 'affectations')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Creneau $creneau = null;

    #[Assert\NotNull]
    #[ORM\ManyToOne(targetEntity: Utilisateur::class, inversedBy: 'affectationsCreneau')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $utilisateur = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?TypeAffectation
    {
        return $this->type;
    }

    public function setType(TypeAffectation $type): static
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

    public function setCreneau(?Creneau $creneau): static
    {
        $this->creneau = $creneau;

        return $this;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): static
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    /**
     * Vrai si la période [dateDebut ; dateFin] couvre la date donnée (dateFin null = sans fin).
     */
    public function estActifLe(\DateTimeInterface $date): bool
    {
        return null !== $this->dateDebut
            && $this->dateDebut <= $date
            && (null === $this->dateFin || $this->dateFin >= $date);
    }
}
