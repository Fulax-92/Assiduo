<?php

namespace App\Entity;

use App\Enum\StatutAppel;
use App\Repository\AppelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity(repositoryClass: AppelRepository::class)]
#[ORM\UniqueConstraint(name: 'uniq_appel_creneau_date', columns: ['creneau_id', 'date_appel'])]
#[UniqueEntity(fields: ['creneau', 'dateAppel'], message: 'Un appel existe déjà pour ce créneau à cette date.')]
class Appel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotNull]
    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $dateAppel = null;

    #[ORM\Column(enumType: StatutAppel::class, length: 20)]
    private StatutAppel $statut = StatutAppel::Brouillon;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $ouvertLe = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $valideLe = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $verrouilleLe = null;

    #[Assert\NotNull]
    #[ORM\ManyToOne(targetEntity: Utilisateur::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $creePar = null;

    #[ORM\ManyToOne(targetEntity: Utilisateur::class)]
    private ?Utilisateur $validePar = null;

    #[Assert\NotNull]
    #[ORM\ManyToOne(targetEntity: Creneau::class, inversedBy: 'appels')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Creneau $creneau = null;

    /**
     * @var Collection<int, Presence>
     */
    #[ORM\OneToMany(targetEntity: Presence::class, mappedBy: 'appel', cascade: ['persist'])]
    private Collection $presences;

    public function __construct()
    {
        $this->presences = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateAppel(): ?\DateTimeImmutable
    {
        return $this->dateAppel;
    }

    public function setDateAppel(\DateTimeImmutable $dateAppel): static
    {
        $this->dateAppel = $dateAppel;

        return $this;
    }

    public function getStatut(): StatutAppel
    {
        return $this->statut;
    }

    public function setStatut(StatutAppel $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getOuvertLe(): ?\DateTimeImmutable
    {
        return $this->ouvertLe;
    }

    public function setOuvertLe(?\DateTimeImmutable $ouvertLe): static
    {
        $this->ouvertLe = $ouvertLe;

        return $this;
    }

    public function getValideLe(): ?\DateTimeImmutable
    {
        return $this->valideLe;
    }

    public function setValideLe(?\DateTimeImmutable $valideLe): static
    {
        $this->valideLe = $valideLe;

        return $this;
    }

    public function getVerrouilleLe(): ?\DateTimeImmutable
    {
        return $this->verrouilleLe;
    }

    public function setVerrouilleLe(?\DateTimeImmutable $verrouilleLe): static
    {
        $this->verrouilleLe = $verrouilleLe;

        return $this;
    }

    public function getCreePar(): ?Utilisateur
    {
        return $this->creePar;
    }

    public function setCreePar(?Utilisateur $creePar): static
    {
        $this->creePar = $creePar;

        return $this;
    }

    public function getValidePar(): ?Utilisateur
    {
        return $this->validePar;
    }

    public function setValidePar(?Utilisateur $validePar): static
    {
        $this->validePar = $validePar;

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

    /**
     * @return Collection<int, Presence>
     */
    public function getPresences(): Collection
    {
        return $this->presences;
    }

    public function addPresence(Presence $presence): static
    {
        if (!$this->presences->contains($presence)) {
            $this->presences->add($presence);
            $presence->setAppel($this);
        }

        return $this;
    }

    public function removePresence(Presence $presence): static
    {
        if ($this->presences->removeElement($presence)) {
            // set the owning side to null (unless already changed)
            if ($presence->getAppel() === $this) {
                $presence->setAppel(null);
            }
        }

        return $this;
    }

    #[Assert\Callback]
    public function validerCoherenceStatut(ExecutionContextInterface $context): void
    {
        $estValide = \in_array($this->statut, [StatutAppel::Valide, StatutAppel::Verrouille], true);

        if ($estValide && (null === $this->valideLe || null === $this->validePar)) {
            $context->buildViolation('Un appel validé doit avoir une date et un auteur de validation.')
                ->atPath('validePar')
                ->addViolation();
        }

        if (StatutAppel::Verrouille === $this->statut && null === $this->verrouilleLe) {
            $context->buildViolation('Un appel verrouillé doit avoir une date de verrouillage.')
                ->atPath('verrouilleLe')
                ->addViolation();
        }
    }
}
