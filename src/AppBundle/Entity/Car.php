<?php


namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Cars
 * @package AppBundle\Entity
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CarRepository")
 */
class Car
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;
    private $id1;//only for fixtures

    /**
     * @ORM\Column(type="string")
     */
    private $model;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="City",
     *     inversedBy="car")
     * @ORM\JoinColumn(
     *     name="city_id",
     *     referencedColumnName="id",
     *     nullable=false)
     */
    private $city;

    /**
     * @ORM\Column(type="integer", length=2)
     */
    private $seats;

    /**
     * @ORM\Column(type="string")
     */
    private $fuel;

    /**
     * @ORM\Column(type="string")
     */
    private $gearType;

    /**
     * @ORM\Column(type="string", length=1024)
     */
    private $image;

    /**
     * @ORM\Column(type="boolean")
     */
    private $available;

    /**
     * @ORM\Column(type="decimal", precision=6, scale=2)
     */
    private $price;

    /**
     * @ORM\OneToMany(
     *     targetEntity="AppBundle\Entity\Bookings\CarBooked",
     *     mappedBy="car")
     */
    private $carBooked;

    /**
     * @return mixed
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param mixed $model
     */
    public function setModel($model)
    {
        $this->model = $model;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return mixed
     */
    public function getSeats()
    {
        return $this->seats;
    }

    /**
     * @param mixed $seats
     */
    public function setSeats($seats)
    {
        $this->seats = $seats;
    }

    /**
     * @return mixed
     */
    public function getFuel()
    {
        return $this->fuel;
    }

    /**
     * @param mixed $fuel
     */
    public function setFuel($fuel)
    {
        $this->fuel = $fuel;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image)
    {
        $this->image = $image;
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
    public function getGearType()
    {
        return $this->gearType;
    }

    /**
     * @param mixed $gearType
     */
    public function setGearType($gearType): void
    {
        $this->gearType = $gearType;
    }

    /**
     * @return mixed
     */
    public function getAvailable()
    {
        return $this->available;
    }

    /**
     * @param mixed $available
     */
    public function setAvailable($available): void
    {
        $this->available = $available;
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
    public function setPrice($price): void
    {
        $this->price = $price;
    }

    /**
     * @return mixed
     */
    public function getCarBooked()
    {
        return $this->carBooked;
    }

    /**
     * @param mixed $carBooked
     */
    public function setCarBooked($carBooked): void
    {
        $this->carBooked = $carBooked;
    }
}