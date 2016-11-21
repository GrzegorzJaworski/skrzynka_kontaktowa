<?php

namespace ContactBoxBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Address
 *
 * @ORM\Table(name="address")
 * @ORM\Entity(repositoryClass="ContactBoxBundle\Repository\AddressRepository")
 */
class Address {

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="street", type="string", length=255)
     */
    private $street;

    /**
     * @var string
     *
     * @ORM\Column(name="houseNo", type="string", length=255)
     */
    private $houseNo;

    /**
     * @var int
     *
     * @ORM\Column(name="apartmentNo", type="integer")
     */
    private $apartmentNo;
    
    /**
     *@ORM\OneToMany(targetEntity="Person", mappedBy="address")
     */
    private $person;

    public function __construct() {
        $this->person = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return Address
     */
    public function setCity($city) {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string 
     */
    public function getCity() {
        return $this->city;
    }

    /**
     * Set street
     *
     * @param string $street
     * @return Address
     */
    public function setStreet($street) {
        $this->street = $street;

        return $this;
    }

    /**
     * Get street
     *
     * @return string 
     */
    public function getStreet() {
        return $this->street;
    }

    /**
     * Set houseNo
     *
     * @param string $houseNo
     * @return Address
     */
    public function setHouseNo($houseNo) {
        $this->houseNo = $houseNo;

        return $this;
    }

    /**
     * Get houseNo
     *
     * @return string 
     */
    public function getHouseNo() {
        return $this->houseNo;
    }

    /**
     * Set apartmentNo
     *
     * @param integer $apartmentNo
     * @return Address
     */
    public function setApartmentNo($apartmentNo) {
        $this->apartmentNo = $apartmentNo;

        return $this;
    }

    /**
     * Get apartmentNo
     *
     * @return integer 
     */
    public function getApartmentNo() {
        return $this->apartmentNo;
    }


    /**
     * Add person
     *
     * @param \ContactBoxBundle\Entity\Person $person
     * @return Address
     */
    public function addPerson(\ContactBoxBundle\Entity\Person $person)
    {
        $this->person[] = $person;

        return $this;
    }

    /**
     * Remove person
     *
     * @param \ContactBoxBundle\Entity\Person $person
     */
    public function removePerson(\ContactBoxBundle\Entity\Person $person)
    {
        $this->person->removeElement($person);
    }

    /**
     * Get person
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPerson()
    {
        return $this->person;
    }
}
