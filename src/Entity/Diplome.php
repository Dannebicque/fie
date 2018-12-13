<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DiplomeRepository")
 */
class Diplome
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $sigle;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Offre", mappedBy="diplomes")
     */
    private $offres;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Entreprise", mappedBy="diplomes")
     */
    private $entreprises;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Etudiant", mappedBy="diplome")
     */
    private $etudiants;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $datesstages;

    public function __construct()
    {
        $this->offres = new ArrayCollection();
        $this->entreprises = new ArrayCollection();
        $this->etudiants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getSigle(): ?string
    {
        return $this->sigle;
    }

    public function setSigle(string $sigle): self
    {
        $this->sigle = $sigle;

        return $this;
    }

    /**
     * @return Collection|Offre[]
     */
    public function getOffres(): Collection
    {
        return $this->offres;
    }

    public function addOffre(Offre $offre): self
    {
        if (!$this->offres->contains($offre)) {
            $this->offres[] = $offre;
            $offre->addDiplome($this);
        }

        return $this;
    }

    public function removeOffre(Offre $offre): self
    {
        if ($this->offres->contains($offre)) {
            $this->offres->removeElement($offre);
            $offre->removeDiplome($this);
        }

        return $this;
    }

    /**
     * @return Collection|Entreprise[]
     */
    public function getEntreprises(): Collection
    {
        return $this->entreprises;
    }

    public function addEntreprise(Entreprise $entreprise): self
    {
        if (!$this->entreprises->contains($entreprise)) {
            $this->entreprises[] = $entreprise;
            $entreprise->addDiplome($this);
        }

        return $this;
    }

    public function removeEntreprise(Entreprise $entreprise): self
    {
        if ($this->entreprises->contains($entreprise)) {
            $this->entreprises->removeElement($entreprise);
            $entreprise->removeDiplome($this);
        }

        return $this;
    }

    public function getDisplay() {
        return $this->getLibelle().' ('.$this->getSigle().', période de stage : '.$this->getDatesstages().')';
    }

    /**
     * @return Collection|Etudiant[]
     */
    public function getEtudiants(): Collection
    {
        return $this->etudiants;
    }

    public function addEtudiant(Etudiant $nom): self
    {
        if (!$this->etudiants->contains($nom)) {
            $this->etudiants[] = $nom;
            $nom->setDiplome($this);
        }

        return $this;
    }

    public function removeEtudiant(Etudiant $nom): self
    {
        if ($this->etudiants->contains($nom)) {
            $this->etudiants->removeElement($nom);
            // set the owning side to null (unless already changed)
            if ($nom->getDiplome() === $this) {
                $nom->setDiplome(null);
            }
        }

        return $this;
    }

    public function getDatesstages(): ?string
    {
        return $this->datesstages;
    }

    public function setDatesstages(string $datesstages): self
    {
        $this->datesstages = $datesstages;

        return $this;
    }
}
