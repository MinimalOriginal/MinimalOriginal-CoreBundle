<?php

namespace MinimalOriginal\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use MinimalOriginal\CoreBundle\Annotation\Exposure;

/**
 * App
 *
 * @ORM\Table(name="app")
 * @ORM\Entity(repositoryClass="MinimalOriginal\CoreBundle\Repository\AppRepository")
 */
class App
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
     * @Exposure(groups = {"manager"}, name = "Nom")
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @Exposure(groups = {"manager"}, name = "Attribut")
     * @ORM\Column(name="attr", type="string", length=255, unique=true)
     */
    private $attr;

    /**
     * @var string
     *
     * @Exposure(groups = {"manager"}, name = "Valeur")
     * @ORM\Column(name="value", type="string", length=255, nullable=true)
     */
    private $value;

    public function __toString(){
      return $this->getName();
    }

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
     * Set name
     *
     * @param string $name
     *
     * @return App
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set attr
     *
     * @param string $attr
     *
     * @return App
     */
    public function setAttr($attr)
    {
        $this->attr = $attr;

        return $this;
    }

    /**
     * Get attr
     *
     * @return string
     */
    public function getAttr()
    {
        return $this->attr;
    }

    /**
     * Set value
     *
     * @param string $value
     *
     * @return App
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }
}
