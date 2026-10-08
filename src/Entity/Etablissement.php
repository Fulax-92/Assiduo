<?php

namespace App\Entity;

use App\Repository\EtablissementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EtablissementRepository::class)]
#[UniqueEntity(fields: ['codeUai'], message: 'Ce code UAI est déjà utilisé.')]
class Etablissement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $nom = null;

    #[Assert\NotBlank]
    #[Assert\Regex(pattern: '/^\d{7}[A-Z]$/', message: 'Le code UAI doit contenir 7 chiffres suivis d\'une lettre majuscule.')]
    #[ORM\Column(type: Types::STRING, length: 8, unique: true)]
    private ?string $codeUai = null;

    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $adresse = null;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $actif = true;

    /**
     * @var Collection<int, AnneeScolaire>
     */
    #[ORM\OneToMany(targetEntity: AnneeScolaire::class, mappedBy: 'etablissement')]
    private Collection $anneesScolaires;

    /**
     * @var Collection<int, Classe>
     */
    #[ORM\OneToMany(targetEntity: Classe::class, mappedBy: 'etablissement')]
    private Collection $classes;

    /**
     * @var Collection<int, UtilisateurEtablissement>
     */
    #[ORM\OneToMany(targetEntity: UtilisateurEtablissement::class, mappedBy: 'etablissement')]
    private Collection $utilisateurEtablissements;

    public function __construct()
    {
        $this->anneesScolaires = new ArrayCollection();
        $this->classes = new ArrayCollection();
        $this->utilisateurEtablissements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getCodeUai(): ?string
    {
        return $this->codeUai;
    }

    public function setCodeUai(string $codeUai): static
    {
        $this->codeUai = $codeUai;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function isActif(): bool
    {
        return $this->actif;
    }

    public function setActif(bool $actif): static
    {
        $this->actif = $actif;

        return $this;
    }

    /**
     * @return Collection<int, AnneeScolaire>
     */
    public function getAnneesScolaires(): Collection
    {
        return $this->anneesScolaires;
    }

    public function addAnneeScolaire(AnneeScolaire $anneeScolaire): static
    {
        if (!$this->anneesScolaires->contains($anneeScolaire)) {
            $this->anneesScolaires->add($anneeScolaire);
            $anneeScolaire->setEtablissement($this);
        }

        return $this;
    }

    public function removeAnneeScolaire(AnneeScolaire $anneeScolaire): static
    {
        if ($this->anneesScolaires->removeElement($anneeScolaire)) {
            // set the owning side to null (unless already changed)
            if ($anneeScolaire->getEtablissement() === $this) {
                $anneeScolaire->setEtablissement(null);
            }
        }

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
            $classe->setEtablissement($this);
        }

        return $this;
    }

    public function removeClasse(Classe $classe): static
    {
        if ($this->classes->removeElement($classe)) {
            // set the owning side to null (unless already changed)
            if ($classe->getEtablissement() === $this) {
                $classe->setEtablissement(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UtilisateurEtablissement>
     */
    public function getUtilisateurEtablissements(): Collection
    {
        return $this->utilisateurEtablissements;
    }

    public function addUtilisateurEtablissement(UtilisateurEtablissement $utilisateurEtablissement): static
    {
        if (!$this->utilisateurEtablissements->contains($utilisateurEtablissement)) {
            $this->utilisateurEtablissements->add($utilisateurEtablissement);
            $utilisateurEtablissement->setEtablissement($this);
        }

        return $this;
    }

    public function removeUtilisateurEtablissement(UtilisateurEtablissement $utilisateurEtablissement): static
    {
        if ($this->utilisateurEtablissements->removeElement($utilisateurEtablissement)) {
            // set the owning side to null (unless already changed)
            if ($utilisateurEtablissement->getEtablissement() === $this) {
                $utilisateurEtablissement->setEtablissement(null);
            }
        }

        return $this;
    }
}
