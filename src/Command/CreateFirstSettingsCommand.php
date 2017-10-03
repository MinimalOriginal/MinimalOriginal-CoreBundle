<?php

namespace MinimalOriginal\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use MinimalOriginal\CoreBundle\Entity\App;

class CreateFirstSettingsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
      $this
          ->setName('minimal_core:create-first-settings')
          ->setDescription('Creates the first settings of the site.')
          ->setHelp('This command allows you to create a first settings for the website.')
      ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'First settings creator',
            '============',
            '',
        ]);
        $doctrine = $this->getContainer()->get('doctrine');
        $em = $doctrine->getManager();

        $app = new App();
        $app->setName("Nom de votre site");
        $app->setAttr("siteName");
        $app->setValue("Mon site internet");
        $em->persist($app);

        $app = new App();
        $app->setName("Page d'accueil par défaut");
        $app->setAttr("homePage");
        $app->setValue(null);
        $em->persist($app);

        $em->flush();

        $output->writeln('The first page has been successfully generated!');


    }
}
