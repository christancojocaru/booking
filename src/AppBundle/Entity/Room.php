<?php


namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RoomRepository")
 * @ORM\Table(name="room")
 */
class Room
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

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
     * @ORM\Column(type="boolean")
     */
    private $available;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="Building",
     *     inversedBy="room")
     * @ORM\JoinColumn(
     *     name="building_id",
     *     referencedColumnName="id")
     */
    private $building;

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
    public function getAvailable()
    {
        return $this->available;
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
     * @param mixed $available
     */
    public function setAvailable($available)
    {
        $this->available = $available;
    }

    /**
     * @param mixed $building
     */
    public function setBuilding($building)
    {
        $this->building = $building;
    }
}