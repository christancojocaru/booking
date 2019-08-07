<?php


namespace AppBundle\Entity\Bookings;


use AppBundle\Entity\Room;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="accommodation_book")
 */
class AccommodationBook
{
    public function __construct()
    {
        $this->rooms = new ArrayCollection();
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(
     *     targetEntity="AppBundle\Entity\Room",
     *     inversedBy="roomBooked")
     */
    private $rooms;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="AppBundle\Entity\User",
     *     inversedBy="accommodationBook")
     * @ORM\JoinColumn(
     *     name="user_id",
     *     referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\Column(type="date")
     */
    private $period_start;

    /**
     * @ORM\Column(type="date")
     */
    private $period_end;

    public function getDays()
    {
        $diff = $this->period_end->diff($this->period_start);
        return $diff->d;
    }

    /**
     * @return Collection|Room[]
     */
    public function getRooms(): Collection
    {
        return $this->rooms;
    }
    public function addRoom(Room $room): self
    {
        if (!$this->rooms->contains($room)) {
            $this->rooms[] = $room;
        }
        return $this;
    }
    public function removeRoom(Room $room): self
    {
        if ($this->rooms->contains($room)) {
            $this->rooms->removeElement($room);
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getPeriodStart()
    {
        return $this->period_start;
    }

    /**
     * @param mixed $period_start
     */
    public function setPeriodStart($period_start)
    {
        $this->period_start = $period_start;
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
    public function getPeriodEnd()
    {
        return $this->period_end;
    }

    /**
     * @param mixed $period_end
     */
    public function setPeriodEnd($period_end)
    {
        $this->period_end = $period_end;
    }
}