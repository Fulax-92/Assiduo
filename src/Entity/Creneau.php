<?php

namespace App\Entity;

use App\Repository\CreneauRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity(repositoryClass: CreneauRepository::class)]
#[ORM\Table(name: 'creneau')]
class Creneau
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\NotNull]
    #[Assert\Range(min: 1, max: 7, notInRangeMessage: 'Le jour doit être compris entre 1 (lundi) et 7 (dimanche).')]
    private ?int $jourSemaine = null;

    #[ORM\Column(type: Types::TIME_IMMUTABLE)]
    #[Assert\NotNull]
    private ?\DateTimeImmutable $heureDebut = null;

    #[ORM\Column(type: Types::TIME_IMMUTABLE)]
    #[Assert\NotNull]
    private ?\DateTimeImmutable $heureFin = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private ?string $matiere = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private ?string $salle = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    #[Assert\NotNull]
    private ?\DateTimeImmutable $dateDebutValidite = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    #[Assert\NotNull]
    private ?\DateTimeImmutable $dateFinValidite = null;

    #[ORM\ManyToOne(targetEntity: Classe::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull]
    private ?Classe $classe = null;

    #[ORM\ManyToOne(targetEntity: AnneeScolaire::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull]
    private ?AnneeScolaire $anneeScolaire = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJourSemaine(): ?int
    {
        return $this->jourSemaine;
    }

    public function setJourSemaine(int $jourSemaine): static
    {
        $this->jourSemaine = $jourSemaine;

        return $this;
    }

    public function getHeureDebut(): ?\DateTimeImmutable
    {
        return $this->heureDebut;
    }

    public function setHeureDebut(\DateTimeImmutable $heureDebut): static
    {
        $this->heureDebut = $heureDebut;

        return $this;
    }

    public function getHeureFin(): ?\DateTimeImmutable
    {
        return $this->heureFin;
    }

    public function setHeureFin(\DateTimeImmutable $heureFin): static
    {
        $this->heureFin = $heureFin;

        return $this;
    }

    public function getMatiere(): ?string
    {
        return $this->matiere;
    }

    public function setMatiere(string $matiere): static
    {
        $this->matiere = $matiere;

        return $this;
    }

    public function getSalle(): ?string
    {
        return $this->salle;
    }

    public function setSalle(string $salle): static
    {
        $this->salle = $salle;

        return $this;
    }

    public function getDateDebutValidite(): ?\DateTimeImmutable
    {
        return $this->dateDebutValidite;
    }

    public function setDateDebutValidite(\DateTimeImmutable $dateDebutValidite): static
    {
        $this->dateDebutValidite = $dateDebutValidite;

        return $this;
    }

    public function getDateFinValidite(): ?\DateTimeImmutable
    {
        return $this->dateFinValidite;
    }

    public function setDateFinValidite(\DateTimeImmutable $dateFinValidite): static
    {
        $this->dateFinValidite = $dateFinValidite;

        return $this;
    }

    public function getClasse(): ?Classe
    {
        return $this->classe;
    }

    public function setClasse(Classe $classe): static
    {
        $this->classe = $classe;

        return $this;
    }

    public function getAnneeScolaire(): ?AnneeScolaire
    {
        return $this->anneeScolaire;
    }

    public function setAnneeScolaire(AnneeScolaire $anneeScolaire): static
    {
        $this->anneeScolaire = $anneeScolaire;

        return $this;
    }

    #[Assert\Callback]
    public function validateCoherence(ExecutionContextInterface $context): void
    {
        if ($this->heureDebut !== null && $this->heureFin !== null && $this->heureFin <= $this->heureDebut) {
            $context->buildViolation('La fin du créneau doit être postérieure au début.')->atPath('heureFin')->addViolation();
        }

        if ($this->dateDebutValidite !== null && $this->dateFinValidite !== null && $this->dateFinValidite < $this->dateDebutValidite) {
            $context->buildViolation('La fin de validité doit être postérieure ou égale au début.')->atPath('dateFinValidite')->addViolation();
        }
    }
}
