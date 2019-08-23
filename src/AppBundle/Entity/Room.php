<?php


namespace AppBundle\Entity;


use AppBundle\Entity\Bookings\AccommodationBook;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RoomRepository")
 * @ORM\Table(name="room")
 */
class Room
{
    public function __construct()
    {
        $this->roomBooked = new ArrayCollection();
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;
    private $id1;//only for fixtures


    /**
     * @ORM\Column(type="integer")
     */
    private $number;

    /**
     * @ORM\Column(type="decimal", precision=6, scale=2)
     */
    private $price;

    /**
     * @ORM\Column(type="integer")
     */
    private $beds;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="Building",
     *     inversedBy="rooms")
     * @ORM\JoinColumn(
     *     name="building_id",
     *     referencedColumnName="id")
     */
    private $building;

    /**
     * @ORM\ManyToMany(
     *     targetEntity="AppBundle\Entity\Bookings\AccommodationBook",
     *     mappedBy="rooms")
     */
    private $roomBooked;

    /**
     * @return Collection|AccommodationBook[]
     */
    public function getRoomsBooked(): Collection
    {
        return $this->roomBooked;
    }
    public function addRoomBooked(AccommodationBook $roomBooked): self
    {
        if (!$this->roomBooked->contains($roomBooked)) {
            $this->roomBooked[] = $roomBooked;
            $roomBooked->addRoom($this);
        }
        return $this;
    }
    public function removeRoomBooked(AccommodationBook $article): self
    {
        if ($this->roomBooked->contains($article)) {
            $this->roomBooked->removeElement($article);
            $article->removeRoom($this);
        }
        return $this;
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
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return mixed
     */
    public function getBeds()
    {
        return $this->beds;
    }

    /**
     * @return mixed
     */
    public function getBuilding()
    {
        return $this->building;
    }

    /**
     * @param mixed $number
     */
    public function setNumber($number)
    {
        $this->number = $number;
    }

    /**
     * @param mixed $beds
     */
    public function setBeds($beds)
    {
        $this->beds = $beds;
    }

    /**
     * @param mixed $building
     */
    public function setBuilding($building)
    {
        $this->building = $building;
    }

    /**
     * @return mixed
     */
    public function getRoomBooked()
    {
        return $this->roomBooked;
    }

    /**
     * @param mixed $roomBooked
     */
    public function setRoomBooked($roomBooked)
    {
        $this->roomBooked = $roomBooked;
    }
}