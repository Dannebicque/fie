<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CreneauxRepository")
 */
class Creneaux
{
    public const TAB_CRENEAUX = ['14:00', '14:10', '14:20', '14:30', '14:40', '14:50',
                                 '15:00', '15:10', '15:20', '15:30', '15:40', '15:50',
                                 '16:00', '16:10', '16:20', '16:30', '16:40', '16:50',
                                 '17:00', '17:10', '17:20'];
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Etudiant", inversedBy="creneauxes")
     */
    private $etudiant;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Entreprise", inversedBy="creneauxes")
     */
    private $entreprise;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cv;

    /**
     * @ORM\Column(type="string", length=5)
     */
    private $heure;

    /**
     * @ORM\Column(type="boolean")
     */
    private $indisponible = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEtudiant(): ?Etudiant
    {
        return $this->etudiant;
    }

    public function setEtudiant(?Etudiant $etudiant): self
    {
        $this->etudiant = $etudiant;

        return $this;
    }

    public function getEntreprise(): ?Entreprise
    {
        return $this->entreprise;
    }

    public function setEntreprise(?Entreprise $entreprise): self
    {
        $this->entreprise = $entreprise;

        return $this;
    }

    public function getCv(): ?string
    {
        return $this->cv;
    }

    public function setCv(string $cv): self
    {
        $this->cv = $cv;

        return $this;
    }

    public function getHeure(): ?string
    {
        return $this->heure;
    }

    public function setHeure(string $heure): self
    {
        $this->heure = $heure;

        return $this;
    }

    public function getIndisponible(): ?bool
    {
        return $this->indisponible;
    }

    public function setIndisponible(bool $indisponible): self
    {
        $this->indisponible = $indisponible;

        return $this;
    }
}
