<?php

namespace MinimalOriginal\CoreBundle\Routing\Annotation;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route as BaseRoute;

/**
 * Route.
 *
 * @Annotation
 *
 */
class Route extends BaseRoute
{
    private $title;

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }
}
