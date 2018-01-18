<?php

namespace TobatBundle\Entity;

/**
 * CategorieSociale
 */
class CategorieSociale
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $nomCate;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nomCate
     *
     * @param string $nomCate
     *
     * @return CategorieSociale
     */
    public function setNomCate($nomCate)
    {
        $this->nomCate = $nomCate;
    
        return $this;
    }

    /**
     * Get nomCate
     *
     * @return string
     */
    public function getNomCate()
    {
        return $this->nomCate;
    }
}

