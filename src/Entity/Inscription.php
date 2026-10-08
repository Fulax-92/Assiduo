<?php

namespace App\Entity;

use App\Repository\InscriptionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity(repositoryClass: InscriptionRepository::class)]
#[ORM\Table(name: 'inscription')]
#[ORM\UniqueConstraint(name: 'uniq_inscription_eleve_classe_debut', columns: ['eleve_id', 'classe_id', 'date_debut'])]
#[UniqueEntity(fields: ['eleve', 'classe', 'dateDebut'], errorPath: 'eleve', message: 'Cet élève est déjà inscrit dans cette classe à cette date.')]
class Inscription
{
    public const STATUT_ACTIVE = 'ACTIVE';
    public const STATUT_TERMINEE = 'TERMINEE';
    public const STATUT_ANNULEE = 'ANNULEE';
    public const STATUTS = [self::STATUT_ACTIVE, self::STATUT_TERMINEE, self::STATUT_ANNULEE];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    #[Assert\NotNull]
    private ?\DateTimeImmutable $dateDebut = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $dateFin = null;

    #[ORM\Column(length: 30, options: ['default' => self::STATUT_ACTIVE])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 30)]
    #[Assert\Choice(choices: self::STATUTS)]
    private string $statut = self::STATUT_ACTIVE;

    #[ORM\ManyToOne(targetEntity: Eleve::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull]
    private ?Eleve $eleve = null;

    #[ORM\ManyToOne(targetEntity: Classe::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull]
    private ?Classe $classe = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getStatut(): string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getEleve(): ?Eleve
    {
        return $this->eleve;
    }

    public function setEleve(Eleve $eleve): static
    {
        $this->eleve = $eleve;

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

    #[Assert\Callback]
    public function validateCoherence(ExecutionContextInterface $context): void
    {
        if ($this->dateDebut !== null && $this->dateFin !== null && $this->dateFin < $this->dateDebut) {
            $context->buildViolation('La date de fin doit être postérieure ou égale à la date de début.')->atPath('dateFin')->addViolation();
        }
    }
}
