<?php

namespace App\Entity;

use App\Repository\EleveRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EleveRepository::class)]
#[UniqueEntity(fields: ['ine'], message: 'Cet INE est déjà attribué à un autre élève.')]
class Eleve
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\Regex(pattern: '/^[0-9A-Z]{11}$/', message: 'L\'INE doit contenir 11 caractères alphanumériques majuscules.')]
    #[ORM\Column(type: Types::STRING, length: 11, nullable: true, unique: true)]
    private ?string $ine = null;

    #[Assert\NotBlank]
    #[Assert\Length(max: 100)]
    #[ORM\Column(type: Types::STRING, length: 100)]
    private ?string $nom = null;

    #[Assert\NotBlank]
    #[Assert\Length(max: 100)]
    #[ORM\Column(type: Types::STRING, length: 100)]
    private ?string $prenom = null;

    #[Assert\NotNull]
    #[Assert\LessThan('today')]
    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $dateNaissance = null;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $actif = true;

    /**
     * @var Collection<int, Inscription>
     */
    #[ORM\OneToMany(targetEntity: Inscription::class, mappedBy: 'eleve')]
    private Collection $inscriptions;

    /**
     * @var Collection<int, Justificatif>
     */
    #[ORM\OneToMany(targetEntity: Justificatif::class, mappedBy: 'eleve')]
    private Collection $justificatifs;

    public function __construct()
    {
        $this->inscriptions = new ArrayCollection();
        $this->justificatifs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIne(): ?string
    {
        return $this->ine;
    }

    public function setIne(?string $ine): static
    {
        $this->ine = $ine;

        return $this;
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getDateNaissance(): ?\DateTimeImmutable
    {
        return $this->dateNaissance;
    }

    public function setDateNaissance(\DateTimeImmutable $dateNaissance): static
    {
        $this->dateNaissance = $dateNaissance;

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
     * @return Collection<int, Inscription>
     */
    public function getInscriptions(): Collection
    {
        return $this->inscriptions;
    }

    public function addInscription(Inscription $inscription): static
    {
        if (!$this->inscriptions->contains($inscription)) {
            $this->inscriptions->add($inscription);
            $inscription->setEleve($this);
        }

        return $this;
    }

    public function removeInscription(Inscription $inscription): static
    {
        if ($this->inscriptions->removeElement($inscription)) {
            // set the owning side to null (unless already changed)
            if ($inscription->getEleve() === $this) {
                $inscription->setEleve(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Justificatif>
     */
    public function getJustificatifs(): Collection
    {
        return $this->justificatifs;
    }

    public function addJustificatif(Justificatif $justificatif): static
    {
        if (!$this->justificatifs->contains($justificatif)) {
            $this->justificatifs->add($justificatif);
            $justificatif->setEleve($this);
        }

        return $this;
    }

    public function removeJustificatif(Justificatif $justificatif): static
    {
        if ($this->justificatifs->removeElement($justificatif)) {
            // set the owning side to null (unless already changed)
            if ($justificatif->getEleve() === $this) {
                $justificatif->setEleve(null);
            }
        }

        return $this;
    }
}
