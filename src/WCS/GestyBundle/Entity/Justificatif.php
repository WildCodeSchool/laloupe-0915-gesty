<?php
// WCS\GestyBundle\Entity\Justificatif

namespace WCS\GestyBundle\Entity;

use Vlabs\MediaBundle\Entity\BaseFile as VlabsFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * WCS\GestyBundle\Entity\File
 *
 */
class Justificatif extends VlabsFile
{
    /**
     * @var string $path
     *
     * @Assert\Image()
     */
    private $path;


    /**
     * Set path
     *
     * @param string $path
     * @return Justificatif
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }




    // YAML GENERATED CODE
    
    /**
     * @var \Application\Sonata\UserBundle\Entity\User
     */
    private $signataire;


    /**
     * Set signataire
     *
     * @param \Application\Sonata\UserBundle\Entity\User $signataire
     *
     * @return Justificatif
     */
    public function setSignataire(\Application\Sonata\UserBundle\Entity\User $signataire = null)
    {
        $this->signataire = $signataire;

        return $this;
    }

    /**
     * Get signataire
     *
     * @return \Application\Sonata\UserBundle\Entity\User
     */
    public function getSignataire()
    {
        return $this->signataire;
    }
}
