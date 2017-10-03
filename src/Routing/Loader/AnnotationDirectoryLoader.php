<?php

namespace MinimalOriginal\CoreBundle\Routing\Loader;

use Symfony\Component\Routing\Loader\AnnotationDirectoryLoader as BaseAnnotationDirectoryLoader;

class AnnotationDirectoryLoader extends BaseAnnotationDirectoryLoader
{
    /**
     * {@inheritdoc}
     */
    public function supports($resource, $type = null)
    {
        if (false === is_string($resource)) {
            return false;
        }

        try {
            $path = $this->locator->locate($resource);
        } catch (\Exception $e) {
            return false;
        }

        return is_dir($path) && (!$type || 'minimal_annotation' === $type);
    }
}
