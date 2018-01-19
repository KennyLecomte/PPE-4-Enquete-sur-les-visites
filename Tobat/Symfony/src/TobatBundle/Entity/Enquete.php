<?php

namespace TobatBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Enquete
 *
 * @ORM\Table(name="enquete")
 * @ORM\Entity(repositoryClass="TobatBundle\Repository\EnqueteRepository")
 */
class Enquete
{
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
     * @ORM\Column(name="trancheAge", type="string", length=255)
     */
    private $trancheAge;

    /**
     * @var string
     *
     * @ORM\Column(name="motivation", type="string", length=255)
     */
    private $motivation;

    /**
     * @var string
     *
     * @ORM\Column(name="ville", type="string", length=255)
     */
    private $ville;

    /**
     * @var int
     *
     * @ORM\Column(name="codePostal", type="integer")
     */
    private $codePostal;

    /**
     * @var bool
     *
     * @ORM\Column(name="vip", type="boolean")
     */
    private $vip;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateEnquete", type="date")
     */
    private $dateEnquete;

    /**
     * @ORM\ManyToOne(targetEntity="TobatBundle\Entity\Budget")
     * @ORM\JoinColumn(nullable=false)
     */
    private $budget;

    /**
     * @ORM\ManyToOne(targetEntity="TobatBundle\Entity\Departement")
     * @ORM\JoinColumn(nullable=false)
     */
    private $departement;

    /**
     * @ORM\ManyToOne(targetEntity="TobatBundle\Entity\CategorieSociale")
     * @ORM\JoinColumn(nullable=false)
     */
    private $categorieSociale;

    /**
     * @ORM\ManyToMany(targetEntity="TobatBundle\Entity\Bateau", cascade={"persist"})
     */
    private $bateaux;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set trancheAge
     *
     * @param string $trancheAge
     *
     * @return Enquete
     */
    public function setTrancheAge($trancheAge)
    {
        $this->trancheAge = $trancheAge;

        return $this;
    }

    /**
     * Get trancheAge
     *
     * @return string
     */
    public function getTrancheAge()
    {
        return $this->trancheAge;
    }

    /**
     * Set motivation
     *
     * @param string $motivation
     *
     * @return Enquete
     */
    public function setMotivation($motivation)
    {
        $this->motivation = $motivation;

        return $this;
    }

    /**
     * Get motivation
     *
     * @return string
     */
    public function getMotivation()
    {
        return $this->motivation;
    }

    /**
     * Set ville
     *
     * @param string $ville
     *
     * @return Enquete
     */
    public function setVille($ville)
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * Get ville
     *
     * @return string
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * Set codePostal
     *
     * @param integer $codePostal
     *
     * @return Enquete
     */
    public function setCodePostal($codePostal)
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    /**
     * Get codePostal
     *
     * @return int
     */
    public function getCodePostal()
    {
        return $this->codePostal;
    }

    /**
     * Set vip
     *
     * @param boolean $vip
     *
     * @return Enquete
     */
    public function setVip($vip)
    {
        $this->vip = $vip;

        return $this;
    }

    /**
     * Get vip
     *
     * @return bool
     */
    public function getVip()
    {
        return $this->vip;
    }

    /**
     * Set dateEnquete
     *
     * @param \DateTime $dateEnquete
     *
     * @return Enquete
     */
    public function setDateEnquete($dateEnquete)
    {
        $this->dateEnquete = $dateEnquete;

        return $this;
    }

    /**
     * Get dateEnquete
     *
     * @return \DateTime
     */
    public function getDateEnquete()
    {
        return $this->dateEnquete;
    }

    /**
     * Set budget
     *
     * @param \TobatBundle\Entity\Budget $budget
     *
     * @return Enquete
     */
    public function setBudget(\TobatBundle\Entity\Budget $budget)
    {
        $this->budget = $budget;

        return $this;
    }

    /**
     * Get budget
     *
     * @return \TobatBundle\Entity\Budget
     */
    public function getBudget()
    {
        return $this->budget;
    }

    /**
     * Set departement
     *
     * @param \TobatBundle\Entity\Departement $departement
     *
     * @return Enquete
     */
    public function setDepartement(\TobatBundle\Entity\Departement $departement)
    {
        $this->departement = $departement;

        return $this;
    }

    /**
     * Get departement
     *
     * @return \TobatBundle\Entity\Departement
     */
    public function getDepartement()
    {
        return $this->departement;
    }

    /**
     * Set categorieSociale
     *
     * @param \TobatBundle\Entity\CategorieSociale $categorieSociale
     *
     * @return Enquete
     */
    public function setCategorieSociale(\TobatBundle\Entity\CategorieSociale $categorieSociale)
    {
        $this->categorieSociale = $categorieSociale;

        return $this;
    }

    /**
     * Get categorieSociale
     *
     * @return \TobatBundle\Entity\CategorieSociale
     */
    public function getCategorieSociale()
    {
        return $this->categorieSociale;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->bateaux = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add bateaux
     *
     * @param \TobatBundle\Entity\Bateau $bateaux
     *
     * @return Enquete
     */
    public function addBateaux(\TobatBundle\Entity\Bateau $bateaux)
    {
        $this->bateaux[] = $bateaux;

        return $this;
    }

    /**
     * Remove bateaux
     *
     * @param \TobatBundle\Entity\Bateau $bateaux
     */
    public function removeBateaux(\TobatBundle\Entity\Bateau $bateaux)
    {
        $this->bateaux->removeElement($bateaux);
    }

    /**
     * Get bateaux
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBateaux()
    {
        return $this->bateaux;
    }
}
