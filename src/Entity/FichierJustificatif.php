<?php

namespace App\Entity;

use App\Repository\FichierJustificatifRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: FichierJustificatifRepository::class)]
#[ORM\Table(name: 'fichier_justificatif')]
#[UniqueEntity(fields: ['empreinteSha256'], message: 'Cette valeur est déjà utilisée.')]
class FichierJustificatif
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private ?string $nomOriginal = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 100)]
    private ?string $typeMime = null;

    #[ORM\Column]
    #[Assert\NotNull]
    #[Assert\Positive]
    private ?int $tailleOctets = null;

    #[ORM\Column(length: 500)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 500)]
    private ?string $urlStockage = null;

    #[ORM\Column(length: 64, name: 'empreinte_sha256', unique: true)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 64)]
    #[Assert\Regex(pattern: '/^[a-f0-9]{64}$/i', message: 'Empreinte SHA-256 invalide (64 caractères hexadécimaux).')]
    private ?string $empreinteSha256 = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $ajouteLe = null;

    #[ORM\ManyToOne(targetEntity: Justificatif::class, inversedBy: 'fichiers')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull]
    private ?Justificatif $justificatif = null;

    public function __construct()
    {
        $this->ajouteLe = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomOriginal(): ?string
    {
        return $this->nomOriginal;
    }

    public function setNomOriginal(string $nomOriginal): static
    {
        $this->nomOriginal = $nomOriginal;

        return $this;
    }

    public function getTypeMime(): ?string
    {
        return $this->typeMime;
    }

    public function setTypeMime(string $typeMime): static
    {
        $this->typeMime = $typeMime;

        return $this;
    }

    public function getTailleOctets(): ?int
    {
        return $this->tailleOctets;
    }

    public function setTailleOctets(int $tailleOctets): static
    {
        $this->tailleOctets = $tailleOctets;

        return $this;
    }

    public function getUrlStockage(): ?string
    {
        return $this->urlStockage;
    }

    public function setUrlStockage(string $urlStockage): static
    {
        $this->urlStockage = $urlStockage;

        return $this;
    }

    public function getEmpreinteSha256(): ?string
    {
        return $this->empreinteSha256;
    }

    public function setEmpreinteSha256(string $empreinteSha256): static
    {
        $this->empreinteSha256 = $empreinteSha256;

        return $this;
    }

    public function getAjouteLe(): ?\DateTimeImmutable
    {
        return $this->ajouteLe;
    }

    public function setAjouteLe(\DateTimeImmutable $ajouteLe): static
    {
        $this->ajouteLe = $ajouteLe;

        return $this;
    }

    public function getJustificatif(): ?Justificatif
    {
        return $this->justificatif;
    }

    public function setJustificatif(Justificatif $justificatif): static
    {
        $this->justificatif = $justificatif;

        return $this;
    }
}
