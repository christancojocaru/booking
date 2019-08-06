<?php


namespace AppBundle\Entity\Bookings;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="car_booked")
 */
class CarBooked
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */private $id;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="AppBundle\Entity\Car",
     *     inversedBy="carBooked")
     * @ORM\JoinColumn(
     *     name="car_id",
     *     referencedColumnName="id")
     */
    private $car;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="AppBundle\Entity\User",
     *     inversedBy="carBooked")
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