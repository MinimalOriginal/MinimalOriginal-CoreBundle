<?php

namespace MinimalOriginal\CoreBundle\Composer;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Composer\Script\Event;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

class ScriptHandler
{
    /**
     * Install Assets.
     *
     * @param Event $event
     */
    public static function installAssets(Event $event)
    {
        $webDir = __DIR__ . '/../../../../../web';
        $vendorDir = __DIR__ . '/../../../../../vendor';
        $bowerDir = __DIR__ . '/../../../../../bower_components';

        $dirList = array(
            'Fonts And Images' => array(
                $bowerDir . '/ionicons/fonts' => $webDir . '/assets/fonts-ionicons',
            ),
        );

        self::installLib($event, $dirList);
    }

    /**
     * Install jquery.
     *
     * @param Event $event
     * @param array $dirList
     */
    protected static function installLib(Event $event, array $dirList)
    {
        $fs = new Filesystem();

        foreach ($dirList as $key => $lib) {
            $event->getIO()->write('<info>Installing ' . $key . '.</info>');

            foreach ($lib as $root => $dest) {
                if (true === is_dir($root)) {
                    try {
                        $fs->mirror($root, $dest, null, array('override' => true, 'delete' => true));
                    } catch (IOExceptionInterface $e) {
                        $event->getIO()->write('<error>Error on directory copy ' . $e->getPath() . '</error>');
                    }
                } elseif (true === is_file($root)) {
                    try {
                        $fs->copy($root, $dest, true);
                    } catch (IOExceptionInterface $e) {
                        $event->getIO()->write('<error>Error on file copy ' . $e->getPath() . '</error>');
                    }
                } else {
                    $event->getIO()->write('<error>"' . $root . '" is not a valid directory or file</error>');
                }
            }
        }
    }
}
