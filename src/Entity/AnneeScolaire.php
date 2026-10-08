<?php

namespace App\Entity;

use App\Repository\AnneeScolaireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AnneeScolaireRepository::class)]
#[ORM\UniqueConstraint(name: 'uniq_annee_libelle_etablissement', columns: ['libelle', 'etablissement_id'])]
#[UniqueEntity(fields: ['libelle', 'etablissement'], message: 'Cette année scolaire existe déjà pour cet établissement.')]
class AnneeScolaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[Assert\Regex(pattern: '/^\d{4}-\d{4}$/', message: 'Format attendu : AAAA-AAAA.')]
    #[ORM\Column(type: Types::STRING, length: 20)]
    private ?string $libelle = null;

    #[Assert\NotNull]
    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $dateDebut = null;

    #[Assert\NotNull]
    #[Assert\GreaterThanOrEqual(propertyPath: 'dateDebut', message: 'La date de fin doit être postérieure ou égale à la date de début.')]
    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $dateFin = null;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $cloturee = false;

    #[Assert\NotNull]
    #[ORM\ManyToOne(targetEntity: Etablissement::class, inversedBy: 'anneesScolaires')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Etablissement $etablissement = null;

    /**
     * @var Collection<int, Classe>
     */
    #[ORM\OneToMany(targetEntity: Classe::class, mappedBy: 'anneeScolaire')]
    private Collection $classes;

    /**
     * @var Collection<int, Creneau>
     */
    #[ORM\OneToMany(targetEntity: Creneau::class, mappedBy: 'anneeScolaire')]
    private Collection $creneaux;

    public function __construct()
    {
        $this->classes = new ArrayCollection();
        $this->creneaux = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

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

    public function setDateFin(\DateTimeImmutable $dateFin): static
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function isCloturee(): bool
    {
        return $this->cloturee;
    }

    public function setCloturee(bool $cloturee): static
    {
        $this->cloturee = $cloturee;

        return $this;
    }

    public function getEtablissement(): ?Etablissement
    {
        return $this->etablissement;
    }

    public function setEtablissement(?Etablissement $etablissement): static
    {
        $this->etablissement = $etablissement;

        return $this;
    }

    /**
     * @return Collection<int, Classe>
     */
    public function getClasses(): Collection
    {
        return $this->classes;
    }

    public function addClasse(Classe $classe): static
    {
        if (!$this->classes->contains($classe)) {
            $this->classes->add($classe);
            $classe->setAnneeScolaire($this);
        }

        return $this;
    }

    public function removeClasse(Classe $classe): static
    {
        if ($this->classes->removeElement($classe)) {
            // set the owning side to null (unless already changed)
            if ($classe->getAnneeScolaire() === $this) {
                $classe->setAnneeScolaire(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Creneau>
     */
    public function getCreneaux(): Collection
    {
        return $this->creneaux;
    }

    public function addCreneau(Creneau $creneau): static
    {
        if (!$this->creneaux->contains($creneau)) {
            $this->creneaux->add($creneau);
            $creneau->setAnneeScolaire($this);
        }

        return $this;
    }

    public function removeCreneau(Creneau $creneau): static
    {
        if ($this->creneaux->removeElement($creneau)) {
            // set the owning side to null (unless already changed)
            if ($creneau->getAnneeScolaire() === $this) {
                $creneau->setAnneeScolaire(null);
            }
        }

        return $this;
    }
}
