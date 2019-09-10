<?php


namespace AppBundle\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="user", options={"collate"="utf8_general_ci"})
 * @UniqueEntity(fields={"username"}, message="Se pare că aveți deja un cont!")
 */
class User implements UserInterface
{
    public function __construct()
    {
        $this->accommodationBook = new ArrayCollection();
        $this->rentalBook = new ArrayCollection();
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", unique=true)
     * @Assert\NotBlank()
     * @Assert\Length(
     *     min="5",
     *     minMessage="Numele de utilizator trebuie să conțină cel puțin {{ limit }} caractere!",
     * )
     */
    private $username;

    /**
     * The Encoded password
     * @ORM\Column(type="string")
     * @Assert\Length(
     *     min="5",
     *     minMessage="Numele de utilizator trebuie să conțină cel puțin {{ limit }} caractere!",
     * )
     */
    private $password;

    /**
     * A non-persisted field that's used to create the encoded password.
     *
     * @Assert\NotBlank(groups={"Registration"})
     * @var string
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="json_array")
     */
    private $roles = [];

    /**
     * @ORM\OneToMany(
     *     targetEntity="AppBundle\Entity\Bookings\AccommodationBook",
     *     mappedBy="user")
     */
    private $accommodationBook;

    /**
     * @ORM\OneToMany(
     *     targetEntity="AppBundle\Entity\Bookings\RentalBook",
     *     mappedBy="user")
     */
    private $rentalBook;

    /**
     * @return bool
     */
    public function hasReservation()
    {
        if ($this->accommodationBook->count() >= 1) {
            return True;
        } else {
            return False;
        }
    }

    /**
     * @return bool
     */
    public function hasRental()
    {
        if ($this->rentalBook->count() >= 1) {
            return True;
        } else {
            return False;
        }

    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @param string $plainPassword
     */
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;
        // forces the object to look "dirty" to Doctrine. Avoids
        // Doctrine *not* saving this entity, if only plainPassword changes
        $this->password = null;
    }

    /**
     * @param mixed $roles
     */
    public function setRoles(array $roles)
    {
        $this->roles = $roles;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @return mixed
     */
    public function getRoles()
    {
        return $this->roles;
    }

    public function getSalt()
    {
        // leaving blank - I don't need/have a password!
    }

    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }

    /**
     * @return mixed
     */
    public function getAccommodationBook()
    {
        return $this->accommodationBook;
    }

    /**
     * @param mixed $accommodationBook
     */
    public function setAccommodationBook($accommodationBook)
    {
        $this->accommodationBook = $accommodationBook;
    }

    /**
     * @return mixed
     */
    public function getRentalBook()
    {
        return $this->rentalBook;
    }

    /**
     * @param mixed $rentalBook
     */
    public function setRentalBook($rentalBook): void
    {
        $this->rentalBook = $rentalBook;
    }
}