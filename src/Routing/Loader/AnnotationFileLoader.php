<?php

namespace MinimalOriginal\CoreBundle\Routing\Loader;

use Symfony\Component\Routing\Loader\AnnotationFileLoader as BaseAnnotationFileLoader;

class AnnotationFileLoader extends BaseAnnotationFileLoader
{
    /**
     * {@inheritdoc}
     */
    public function supports($resource, $type = null)
    {
        return is_string($resource) && 'php' === pathinfo($resource, PATHINFO_EXTENSION) && (!$type || 'minimal_annotation' === $type);
    }
}
