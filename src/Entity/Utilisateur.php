<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'Un compte existe déjà avec cet email.')]
class Utilisateur implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[Assert\Length(max: 100)]
    #[ORM\Column(type: Types::STRING, length: 100)]
    private ?string $nom = null;

    #[Assert\NotBlank]
    #[Assert\Length(max: 100)]
    #[ORM\Column(type: Types::STRING, length: 100)]
    private ?string $prenom = null;

    #[Assert\NotBlank]
    #[Assert\Email]
    #[Assert\Length(max: 180)]
    #[ORM\Column(type: Types::STRING, length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $motDePasseHash = null;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $actif = true;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $dernierAccesLe = null;

    /**
     * @var Collection<int, UtilisateurRole>
     */
    #[ORM\OneToMany(targetEntity: UtilisateurRole::class, mappedBy: 'utilisateur')]
    private Collection $utilisateurRoles;

    /**
     * @var Collection<int, UtilisateurEtablissement>
     */
    #[ORM\OneToMany(targetEntity: UtilisateurEtablissement::class, mappedBy: 'utilisateur')]
    private Collection $utilisateurEtablissements;

    /**
     * @var Collection<int, AffectationCreneau>
     */
    #[ORM\OneToMany(targetEntity: AffectationCreneau::class, mappedBy: 'utilisateur')]
    private Collection $affectationsCreneau;

    public function __construct()
    {
        $this->utilisateurRoles = new ArrayCollection();
        $this->utilisateurEtablissements = new ArrayCollection();
        $this->affectationsCreneau = new ArrayCollection();
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getMotDePasseHash(): ?string
    {
        return $this->motDePasseHash;
    }

    public function setMotDePasseHash(string $motDePasseHash): static
    {
        $this->motDePasseHash = $motDePasseHash;

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

    public function getDernierAccesLe(): ?\DateTimeImmutable
    {
        return $this->dernierAccesLe;
    }

    public function setDernierAccesLe(?\DateTimeImmutable $dernierAccesLe): static
    {
        $this->dernierAccesLe = $dernierAccesLe;

        return $this;
    }

    /**
     * @return Collection<int, UtilisateurRole>
     */
    public function getUtilisateurRoles(): Collection
    {
        return $this->utilisateurRoles;
    }

    public function addUtilisateurRole(UtilisateurRole $utilisateurRole): static
    {
        if (!$this->utilisateurRoles->contains($utilisateurRole)) {
            $this->utilisateurRoles->add($utilisateurRole);
            $utilisateurRole->setUtilisateur($this);
        }

        return $this;
    }

    public function removeUtilisateurRole(UtilisateurRole $utilisateurRole): static
    {
        if ($this->utilisateurRoles->removeElement($utilisateurRole)) {
            // set the owning side to null (unless already changed)
            if ($utilisateurRole->getUtilisateur() === $this) {
                $utilisateurRole->setUtilisateur(null);
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
            $utilisateurEtablissement->setUtilisateur($this);
        }

        return $this;
    }

    public function removeUtilisateurEtablissement(UtilisateurEtablissement $utilisateurEtablissement): static
    {
        if ($this->utilisateurEtablissements->removeElement($utilisateurEtablissement)) {
            // set the owning side to null (unless already changed)
            if ($utilisateurEtablissement->getUtilisateur() === $this) {
                $utilisateurEtablissement->setUtilisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, AffectationCreneau>
     */
    public function getAffectationsCreneau(): Collection
    {
        return $this->affectationsCreneau;
    }

    public function addAffectationCreneau(AffectationCreneau $affectationCreneau): static
    {
        if (!$this->affectationsCreneau->contains($affectationCreneau)) {
            $this->affectationsCreneau->add($affectationCreneau);
            $affectationCreneau->setUtilisateur($this);
        }

        return $this;
    }

    public function removeAffectationCreneau(AffectationCreneau $affectationCreneau): static
    {
        if ($this->affectationsCreneau->removeElement($affectationCreneau)) {
            // set the owning side to null (unless already changed)
            if ($affectationCreneau->getUtilisateur() === $this) {
                $affectationCreneau->setUtilisateur(null);
            }
        }

        return $this;
    }

    /**
     * Identifiant de connexion utilisé par Symfony Security (l'email).
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * Rôles Symfony déduits des affectations de rôle actives à la date du jour
     * (ex. code ENSEIGNANT => ROLE_ENSEIGNANT).
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $today = new \DateTimeImmutable('today');
        $roles = ['ROLE_USER'];

        foreach ($this->utilisateurRoles as $utilisateurRole) {
            if ($utilisateurRole->estActifLe($today) && null !== $code = $utilisateurRole->getRole()?->getCode()) {
                $roles[] = 'ROLE_'.$code;
            }
        }

        return array_values(array_unique($roles));
    }

    public function getPassword(): ?string
    {
        return $this->motDePasseHash;
    }
}
