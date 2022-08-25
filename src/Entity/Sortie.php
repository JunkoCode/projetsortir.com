<?php

namespace App\Entity;

use App\Repository\SortieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SortieRepository::class)]
class Sortie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull]
    private ?string $nom = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotNull]
    private ?\DateTimeInterface $dateLimiteInscription = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotNull]
    private ?\DateTimeInterface $dateHeureDebut = null;

    #[ORM\Column(nullable: true)]

    private ?\DateInterval $duree = null;

    #[ORM\Column]
    #[Assert\NotNull]
    private ?int $nombreInscriptionMax = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $infoSortie = null;

    #[ORM\ManyToOne(targetEntity: Etat::class ,inversedBy: 'sorties')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull]
    private ?Etat $etat = null;

    #[ORM\ManyToOne(targetEntity: Lieu::class,inversedBy: 'sorties')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Lieu $lieu = null;

    #[ORM\ManyToOne(targetEntity: Utilisateur::class, inversedBy: 'proprietaireSorties')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $organisateur = null;

    #[ORM\ManyToMany(targetEntity: Utilisateur::class, mappedBy: '$participantSorties')]
    private Collection $participants;

    public function __construct()
    {
        $this->participants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDateLimiteInscription(): ?\DateTimeInterface
    {
        return $this->dateLimiteInscription;
    }

    public function setDateLimiteInscription(\DateTimeInterface $dateLimiteInscription): self
    {
        $this->dateLimiteInscription = $dateLimiteInscription;

        return $this;
    }

    public function getDateHeureDebut(): ?\DateTimeInterface
    {
        return $this->dateHeureDebut;
    }

    public function setDateHeureDebut(\DateTimeInterface $dateHeureDebut): self
    {
        $this->dateHeureDebut = $dateHeureDebut;

        return $this;
    }

    public function getDuree(): ?\DateInterval
    {
        return $this->duree;
    }

    public function setDuree(?\DateInterval $duree): self
    {
        $this->duree = $duree;

        return $this;
    }

    public function getNombreInscriptionMax(): ?int
    {
        return $this->nombreInscriptionMax;
    }

    public function setNombreInscriptionMax(int $nombreInscriptionMax): self
    {
        $this->nombreInscriptionMax = $nombreInscriptionMax;

        return $this;
    }

    public function getInfoSortie(): ?string
    {
        return $this->infoSortie;
    }

    public function setInfoSortie(string $infoSortie): self
    {
        $this->infoSortie = $infoSortie;

        return $this;
    }

    public function getEtat(): ?Etat
    {
        return $this->etat;
    }

    public function setEtat(?Etat $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getLieu(): ?Lieu
    {
        return $this->lieu;
    }

    public function setLieu(?Lieu $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getOrganisateur(): ?Utilisateur
    {
        return $this->organisateur;
    }

    public function setOrganisateur(?Utilisateur $organisateur): self
    {
        $this->organisateur = $organisateur;

        return $this;
    }

    /**
     * @return Collection<int, Utilisateur>
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(Utilisateur $participant): self
    {
        if (!$this->participants->contains($participant)) {
            $this->participants->add($participant);
            $participant->addParticipantSorty($this);
        }

        return $this;
    }

    public function removeParticipant(Utilisateur $participant): self
    {
        if ($this->participants->removeElement($participant)) {
            $participant->removeParticipantSorty($this);
        }

        return $this;
    }
}
