<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EntrepriseRepository")
 */
class Entreprise
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Offre", mappedBy="entreprise", cascade={"PERSIST"})
     */
    private $offres;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $societe;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=5)
     */
    private $cp;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ville;

    /**
     * @ORM\Column(type="boolean")
     */
    private $presentation_entreprise;

    /**
     * @ORM\Column(type="boolean")
     */
    private $jobdating;

    /**
     * @ORM\Column(type="boolean")
     */
    private $potcloture;

    /**
     * @ORM\Column(type="datetime")
     */
    private $datedepot;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Representant", mappedBy="entreprise", cascade={"PERSIST"})
     */
    private $representants;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Diplome", inversedBy="entreprises")
     */
    private $diplomes;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $remarques;

    /**
     * @ORM\Column(type="boolean")
     */
    private $paspresent;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbtables = 1;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbchaises = 2;

    /**
     * @ORM\Column(type="boolean")
     */
    private $prise = false;

    /**
     * @ORM\Column(type="integer")
     */
    private $numerostand = 0;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Creneaux", mappedBy="entreprise")
     */
    private $creneauxes;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $salle = '-';

    public function __construct()
    {
        $this->datedepot = new \DateTime('now');
        $this->offres = new ArrayCollection();
        $this->representants = new ArrayCollection();
        $this->diplomes = new ArrayCollection();
        $this->creneauxes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
            $offre->setEntreprise($this);
        }

        return $this;
    }

    public function removeOffre(Offre $offre): self
    {
        if ($this->offres->contains($offre)) {
            $this->offres->removeElement($offre);
            // set the owning side to null (unless already changed)
            if ($offre->getEntreprise() === $this) {
                $offre->setEntreprise(null);
            }
        }

        return $this;
    }

    public function getSociete(): ?string
    {
        return $this->societe;
    }

    public function setSociete(string $societe): self
    {
        $this->societe = $societe;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getCp(): ?string
    {
        return $this->cp;
    }

    public function setCp(string $cp): self
    {
        $this->cp = $cp;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function setFonction(string $fonction): self
    {
        $this->fonction = $fonction;

        return $this;
    }

    public function getPresentationEntreprise(): ?bool
    {
        return $this->presentation_entreprise;
    }

    public function setPresentationEntreprise(bool $presentation_entreprise): self
    {
        $this->presentation_entreprise = $presentation_entreprise;

        return $this;
    }

    public function getJobdating(): ?bool
    {
        return $this->jobdating;
    }

    public function setJobdating(bool $jobdating): self
    {
        $this->jobdating = $jobdating;

        return $this;
    }

    public function getPotcloture(): ?bool
    {
        return $this->potcloture;
    }

    public function setPotcloture(bool $potcloture): self
    {
        $this->potcloture = $potcloture;

        return $this;
    }

    public function getDatedepot(): ?\DateTimeInterface
    {
        return $this->datedepot;
    }

    public function setDatedepot(\DateTimeInterface $datedepot): self
    {
        $this->datedepot = $datedepot;

        return $this;
    }

    /**
     * @return Collection|Representant[]
     */
    public function getRepresentants(): Collection
    {
        return $this->representants;
    }

    public function addRepresentant(Representant $representant): self
    {
        if (!$this->representants->contains($representant)) {
            $this->representants[] = $representant;
            $representant->setEntreprise($this);
        }

        return $this;
    }

    public function removeRepresentant(Representant $representant): self
    {
        if ($this->representants->contains($representant)) {
            $this->representants->removeElement($representant);
            // set the owning side to null (unless already changed)
            if ($representant->getEntreprise() === $this) {
                $representant->setEntreprise(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Diplome[]
     */
    public function getDiplomes(): Collection
    {
        return $this->diplomes;
    }

    public function addDiplome(Diplome $diplome): self
    {
        if (!$this->diplomes->contains($diplome)) {
            $this->diplomes[] = $diplome;
        }

        return $this;
    }

    public function removeDiplome(Diplome $diplome): self
    {
        if ($this->diplomes->contains($diplome)) {
            $this->diplomes->removeElement($diplome);
        }

        return $this;
    }

    public function getRemarques(): ?string
    {
        return $this->remarques;
    }

    public function setRemarques(string $remarques): self
    {
        $this->remarques = $remarques;

        return $this;
    }

    public function display() {
        return $this->societe.' ('.$this->ville.', '.$this->cp.')';
    }

    public function getPaspresent(): ?bool
    {
        return $this->paspresent;
    }

    public function setPaspresent(bool $paspresent): self
    {
        $this->paspresent = $paspresent;

        return $this;
    }

    public function getNbtables(): ?int
    {
        return $this->nbtables;
    }

    public function setNbtables(int $nbtables): self
    {
        $this->nbtables = $nbtables;

        return $this;
    }

    public function getNbchaises(): ?int
    {
        return $this->nbchaises;
    }

    public function setNbchaises(int $nbchaises): self
    {
        $this->nbchaises = $nbchaises;

        return $this;
    }

    public function getPrise(): ?bool
    {
        return $this->prise;
    }

    public function setPrise(bool $prise): self
    {
        $this->prise = $prise;

        return $this;
    }

    public function getNumerostand()
    {
        return $this->numerostand;
    }

    public function setNumerostand($numerostand = 0): self
    {
        $this->numerostand = $numerostand;

        return $this;
    }

    /**
     * @return Collection|Creneaux[]
     */
    public function getCreneauxes(): Collection
    {
        return $this->creneauxes;
    }

    public function addCreneaux(Creneaux $creneaux): self
    {
        if (!$this->creneauxes->contains($creneaux)) {
            $this->creneauxes[] = $creneaux;
            $creneaux->setEntreprise($this);
        }

        return $this;
    }

    public function removeCreneaux(Creneaux $creneaux): self
    {
        if ($this->creneauxes->contains($creneaux)) {
            $this->creneauxes->removeElement($creneaux);
            // set the owning side to null (unless already changed)
            if ($creneaux->getEntreprise() === $this) {
                $creneaux->setEntreprise(null);
            }
        }

        return $this;
    }

    public function getSalle(): ?string
    {
        return $this->salle;
    }

    public function setSalle(string $salle): self
    {
        $this->salle = $salle;

        return $this;
    }
}
