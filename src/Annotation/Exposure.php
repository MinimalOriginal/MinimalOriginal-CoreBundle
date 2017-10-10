<?php

namespace MinimalOriginal\CoreBundle\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
class Exposure
{
    /**
     * @Required
     *
     * @var array
     */
    public $groups;

    /**
     * @Required
     *
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $type;

    /**
     * @return array
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

}
