<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EtudiantRepository")
 */
class Etudiant extends User implements \Serializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Diplome", inversedBy="etudiants")
     */
    private $diplome;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Candidature", mappedBy="etudiant")
     */
    private $candidatures;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Creneaux", mappedBy="etudiant")
     */
    private $creneauxes;

    public function __construct()
    {
        $this->candidatures = new ArrayCollection();
        $this->setRoles(['ROLE_ETUDIANT']);
        $this->creneauxes = new ArrayCollection();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDiplome(): ?Diplome
    {
        return $this->diplome;
    }

    public function setDiplome(?Diplome $diplome): self
    {
        $this->diplome = $diplome;

        return $this;
    }

    /**
     * @return Collection|Candidature[]
     */
    public function getCandidatures(): Collection
    {
        return $this->candidatures;
    }

    public function addCandidature(Candidature $candidature): self
    {
        if (!$this->candidatures->contains($candidature)) {
            $this->candidatures[] = $candidature;
            $candidature->setEtudiant($this);
        }

        return $this;
    }

    public function removeCandidature(Candidature $candidature): self
    {
        if ($this->candidatures->contains($candidature)) {
            $this->candidatures->removeElement($candidature);
            // set the owning side to null (unless already changed)
            if ($candidature->getEtudiant() === $this) {
                $candidature->setEtudiant(null);
            }
        }

        return $this;
    }

    /**
     * String representation of object
     * @link  http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     * @since 5.1.0
     */
    public function serialize(): string
    {
        // Ajouté pour le problème de connexion avec le usernametoken
        return serialize(array(
            $this->id,
            $this->password,
            $this->email
        ));
    }

    /**
     * Constructs the object
     * @link  http://php.net/manual/en/serializable.unserialize.php
     *
     * @param string $serialized <p>
     *                           The string representation of the object.
     *                           </p>
     *
     * @return void
     * @since 5.1.0
     */
    public function unserialize($serialized): void
    {
        // Ajouté pour le problème de connexion avec le usernametoken
        [
            $this->id,
            $this->password,
            $this->email
        ] = unserialize($serialized, ['allowed_classes' => false]);
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
            $creneaux->setEtudiant($this);
        }

        return $this;
    }

    public function removeCreneaux(Creneaux $creneaux): self
    {
        if ($this->creneauxes->contains($creneaux)) {
            $this->creneauxes->removeElement($creneaux);
            // set the owning side to null (unless already changed)
            if ($creneaux->getEtudiant() === $this) {
                $creneaux->setEtudiant(null);
            }
        }

        return $this;
    }

    public function display() {
        return ucfirst($this->getPrenom()).' '.mb_strtoupper($this->getNom());
    }
}
