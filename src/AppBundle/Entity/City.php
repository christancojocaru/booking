<?php


namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CityRepository")
 * @ORM\Table(name="city", options={"collate"="utf8_general_ci"})
 */
class City
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=1024)
     */
    private $image;

    /**
     * @ORM\OneToMany(
     *     targetEntity="Building",
     *     mappedBy="city")
     */
    private $buildings;

    /**
     * @ORM\OneToMany(
     *     targetEntity="Car",
     *     mappedBy="city"
     * )
     */
    private $car;

    /**
     * @var int $averagePrice
     */
    private $averagePrice;

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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getBuildings()
    {
        return $this->buildings;
    }

    /**
     * @param mixed $buildings
     */
    public function setBuildings($buildings)
    {
        $this->buildings = $buildings;
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
     * @return int
     */
    public function getAveragePrice()
    {
        return $this->averagePrice;
    }

    /**
     * @param int $averagePrice
     */
    public function setAveragePrice($averagePrice)
    {
        $this->averagePrice = $averagePrice;
    }

    /**
     * @return mixed
     */
    public function getCar()
    {
        return $this->car;
    }

    /**
     * @param mixed $car
     */
    public function setCar($car)
    {
        $this->car = $car;
    }
}