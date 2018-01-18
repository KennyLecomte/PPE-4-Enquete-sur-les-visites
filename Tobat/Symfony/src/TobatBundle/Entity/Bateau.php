<?php

namespace TobatBundle\Entity;

/**
 * Bateau
 */
class Bateau
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
     * @var string
     */
    private $categorie;


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
     * @return Bateau
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

    /**
     * Set categorie
     *
     * @param string $categorie
     *
     * @return Bateau
     */
    public function setCategorie($categorie)
    {
        $this->categorie = $categorie;
    
        return $this;
    }

    /**
     * Get categorie
     *
     * @return string
     */
    public function getCategorie()
    {
        return $this->categorie;
    }
}

