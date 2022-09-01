<?php /** @noinspection ALL */

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
#[UniqueEntity(fields: ['pseudo'], message: 'Ce pseudo est déja utilisé')]
#[UniqueEntity(fields: ['email'], message: 'Votre email est déja utilisé !')]
#[ORM\HasLifecycleCallbacks]
class Utilisateur implements UserInterface, PasswordAuthenticatedUserInterface
{
    const ROLE_USER = 'ROLE_USER';
    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_ACTIF = 'ROLE_ACTIF';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\UniqueEntity(message: "Votre email est deja utilisé !")]
    #[Assert\Email]
    #[Assert\NotBlank]
    private ?string $email = null;

    //#[ORM\Column]
    //#[Assert\NotBlank]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;


    #[Assert\EqualTo(propertyPath: 'password', message: "Votre mot de passe n'est pas identique !")]
    private ?string $passwordConfirm = null;


    #[ORM\Column(length: 30, unique: true)]
    #[Assert\UniqueEntity(message: "Ce pseudo est déja utilisé")]
    #[Assert\NotBlank]
    private ?string $pseudo = null;

    #[ORM\Column(length: 30)]
    #[Assert\Length(max: 30, maxMessage: "Le nom ne doit pas dépasser 30 caractères !")]
    #[Assert\NotBlank]
    private ?string $nom = null;

    #[ORM\Column(length: 30)]
    #[Assert\Length(max: 30, maxMessage: "Le prénom ne doit pas dépasser 30 caractères !")]
    #[Assert\NotBlank]
    private ?string $prenom = null;

    #[ORM\Column(length: 15)]
    #[Assert\Length(min: 8, max: 20, minMessage: "Le telephone n'est pas au bon format", maxMessage: "Le telephone n'est pas au bon format")]
    #[Assert\Regex(pattern: "/^[0-9]*$/", message: "Votre numéro n'est pas au bon format")]
    private ?string $telephone = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photo = null;

    #[ORM\Column(type: 'boolean')]
    private ?bool $administrateur = null;

    #[ORM\Column(type: 'boolean')]
    private ?bool $actif = null;

    #[MaxDepth(1)]
    #[ORM\ManyToOne(inversedBy: 'utilisateurs')]
    #[ORM\JoinColumn(nullable: true)]
    //#[Assert\Choice(choices: Campus::class, message: 'Veuillez choisir un campus dans la liste')]
    #[Assert\Type(Campus::class)]
    #[Assert\Valid]
    private ?Campus $campus = null;

    #[MaxDepth(1)]
    #[ORM\OneToMany(mappedBy: 'organisateur', targetEntity: Sortie::class, orphanRemoval: true )]
    private Collection $proprietaireSorties;

    #[MaxDepth(1)]
    #[ORM\ManyToMany(targetEntity: Sortie::class, inversedBy: 'participants')]
    private Collection $participantSorties;

    #[ORM\PrePersist]
    public function SetCreatedValues(): void
    {
        if ($this->administrateur == null) {
            $this->administrateur = false;
        }
        if ($this->actif == null) {
            $this->actif = true;
        }
    }

    public function __construct()
    {

        $this->proprietaireSorties = new ArrayCollection();
        $this->participantSorties = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string)$this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string)$this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $this->roles[] = self::ROLE_USER;
        if ($this->administrateur == true) {
            $this->roles[] = self::ROLE_ADMIN;
        }
        if ($this->actif == true) {
            $this->roles[] = self::ROLE_ACTIF;
        }

        return array_unique($this->roles);
    }

    /*public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function addRole(string $role): self
    {
        if (!in_array($role, $this->roles)) {
            $this->roles[] = $role;
        }
        return $this;
    }

    public function removeRole(string $role): self
    {
        if (($key = array_search($role, $this->roles)) !== false) {
            unset($this->roles[$key]);
        }

        return $this;
    }*/

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = ucwords($nom);

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = ucwords($prenom);

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function isAdministrateur(): ?bool
    {
        return $this->administrateur;
    }

    public function setAdministrateur(?bool $administrateur): self
    {
        $this->administrateur = $administrateur;

        return $this;
    }

    public function isActif(): ?bool
    {
        return $this->actif;
    }

    public function setActif(bool $actif): self
    {
        $this->actif = $actif;

        return $this;
    }

    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    public function setCampus(?Campus $campus): self
    {
        $this->campus = $campus;

        return $this;
    }

    /**
     * @return Collection<int, Sortie>
     */
    public function getProprietaireSorties(): Collection
    {
        return $this->proprietaireSorties;
    }

    public function addProprietaireSorty(Sortie $proprietaireSorty): self
    {
        if (!$this->proprietaireSorties->contains($proprietaireSorty)) {
            $this->proprietaireSorties->add($proprietaireSorty);
            $proprietaireSorty->setOrganisateur($this);
        }

        return $this;
    }

    public function removeProprietaireSorty(Sortie $proprietaireSorty): self
    {
        if ($this->proprietaireSorties->removeElement($proprietaireSorty)) {
            // set the owning side to null (unless already changed)
            if ($proprietaireSorty->getOrganisateur() === $this) {
                $proprietaireSorty->setOrganisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Sortie>
     */
    public function getParticipantSorties(): Collection
    {
        return $this->participantSorties;
    }

    public function addParticipantSorty(Sortie $participantSorty): self
    {
        if (!$this->participantSorties->contains($participantSorty)) {
            $this->participantSorties->add($participantSorty);
        }

        return $this;
    }

    public function removeParticipantSorty(Sortie $participantSorty): self
    {
        $this->participantSorties->removeElement($participantSorty);
        $participantSorty->removeParticipant($this);

        return $this;
    }

    public function __toString(): string
    {
        return $this->getPrenom();
    }


    /**
     * @return string|null
     */
    public function getPasswordConfirm(): ?string
    {
        return $this->passwordConfirm;
    }

    /**
     * @param string|null $passwordConfirm
     */
    public function setPasswordConfirm(?string $passwordConfirm): void
    {
        $this->passwordConfirm = $passwordConfirm;
    }
}
