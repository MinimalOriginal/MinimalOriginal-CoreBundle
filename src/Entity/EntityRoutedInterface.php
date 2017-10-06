<?php

namespace MinimalOriginal\CoreBundle\Entity;

interface EntityRoutedInterface{

  public function getId();

  public function getSlug();

  public function getTitle();

  public function getShowRoute();

}
