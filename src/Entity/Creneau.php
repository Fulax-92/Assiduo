<?php

namespace App\Entity;

use App\Repository\CreneauRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity(repositoryClass: CreneauRepository::class)]
class Creneau
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotNull]
    #[Assert\Range(min: 1, max: 7, notInRangeMessage: 'Le jour doit être compris entre 1 (lundi) et 7 (dimanche).')]
    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $jourSemaine = null;

    #[Assert\NotNull]
    #[ORM\Column(type: Types::TIME_IMMUTABLE)]
    private ?\DateTimeImmutable $heureDebut = null;

    #[Assert\NotNull]
    #[Assert\GreaterThan(propertyPath: 'heureDebut', message: 'L\'heure de fin doit être après l\'heure de début.')]
    #[ORM\Column(type: Types::TIME_IMMUTABLE)]
    private ?\DateTimeImmutable $heureFin = null;

    #[Assert\NotBlank]
    #[Assert\Length(max: 100)]
    #[ORM\Column(type: Types::STRING, length: 100)]
    private ?string $matiere = null;

    #[Assert\NotBlank]
    #[Assert\Length(max: 50)]
    #[ORM\Column(type: Types::STRING, length: 50)]
    private ?string $salle = null;

    #[Assert\NotNull]
    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $dateDebutValidite = null;

    #[Assert\NotNull]
    #[Assert\GreaterThanOrEqual(propertyPath: 'dateDebutValidite', message: 'La fin de validité doit être postérieure ou égale au début.')]
    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $dateFinValidite = null;

    #[Assert\NotNull]
    #[ORM\ManyToOne(targetEntity: Classe::class, inversedBy: 'creneaux')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Classe $classe = null;

    #[Assert\NotNull]
    #[ORM\ManyToOne(targetEntity: AnneeScolaire::class, inversedBy: 'creneaux')]
    #[ORM\JoinColumn(nullable: false)]
    private ?AnneeScolaire $anneeScolaire = null;

    /**
     * @var Collection<int, AffectationCreneau>
     */
    #[ORM\OneToMany(targetEntity: AffectationCreneau::class, mappedBy: 'creneau')]
    private Collection $affectations;

    /**
     * @var Collection<int, Appel>
     */
    #[ORM\OneToMany(targetEntity: Appel::class, mappedBy: 'creneau')]
    private Collection $appels;

    public function __construct()
    {
        $this->affectations = new ArrayCollection();
        $this->appels = new ArrayCollection();
    }

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

    public function setClasse(?Classe $classe): static
    {
        $this->classe = $classe;

        return $this;
    }

    public function getAnneeScolaire(): ?AnneeScolaire
    {
        return $this->anneeScolaire;
    }

    public function setAnneeScolaire(?AnneeScolaire $anneeScolaire): static
    {
        $this->anneeScolaire = $anneeScolaire;

        return $this;
    }

    /**
     * @return Collection<int, AffectationCreneau>
     */
    public function getAffectations(): Collection
    {
        return $this->affectations;
    }

    public function addAffectation(AffectationCreneau $affectation): static
    {
        if (!$this->affectations->contains($affectation)) {
            $this->affectations->add($affectation);
            $affectation->setCreneau($this);
        }

        return $this;
    }

    public function removeAffectation(AffectationCreneau $affectation): static
    {
        if ($this->affectations->removeElement($affectation)) {
            // set the owning side to null (unless already changed)
            if ($affectation->getCreneau() === $this) {
                $affectation->setCreneau(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Appel>
     */
    public function getAppels(): Collection
    {
        return $this->appels;
    }

    public function addAppel(Appel $appel): static
    {
        if (!$this->appels->contains($appel)) {
            $this->appels->add($appel);
            $appel->setCreneau($this);
        }

        return $this;
    }

    public function removeAppel(Appel $appel): static
    {
        if ($this->appels->removeElement($appel)) {
            // set the owning side to null (unless already changed)
            if ($appel->getCreneau() === $this) {
                $appel->setCreneau(null);
            }
        }

        return $this;
    }

    /**
     * Le créneau et sa classe doivent appartenir à la même année scolaire
     * (id_annee_scolaire est dénormalisé dans CRENEAU).
     */
    #[Assert\Callback]
    public function validerAnneeScolaire(ExecutionContextInterface $context): void
    {
        if (null !== $this->classe && null !== $this->anneeScolaire
            && $this->classe->getAnneeScolaire() !== $this->anneeScolaire) {
            $context->buildViolation('L\'année scolaire du créneau doit être celle de la classe.')
                ->atPath('anneeScolaire')
                ->addViolation();
        }
    }
}
