<?php

namespace TobatBundle\Entity;

/**
 * Preferer
 */
class Preferer
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $modele;


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
     * Set modele
     *
     * @param string $modele
     *
     * @return Preferer
     */
    public function setModele($modele)
    {
        $this->modele = $modele;
    
        return $this;
    }

    /**
     * Get modele
     *
     * @return string
     */
    public function getModele()
    {
        return $this->modele;
    }
}

