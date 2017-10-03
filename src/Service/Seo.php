<?php

namespace MinimalOriginal\CoreBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\ParameterBag;

use MinimalOriginal\CoreBundle\Util\TextParser;

class Seo
{
    protected $titles = [];
    protected $description = "";
    protected $image;

    protected $metadatas;

    public function __construct()
    {
      $this->metadatas = new ParameterBag();
    }

    /**
    * @param string $title
    *
    * @return Seo
    */
    public function addTitle($title = null)
    {
      if( null !== $title ){
        $this->titles[] = $title;
        $this->metadatas->set(
          'title',
          array(
            $this->createMetaProperty('og:title',$this->getTitle()),
            $this->createMetaProperty('twitter:title',$this->getTitle()),
          )
        );
      }
      return $this;
    }

    /**
    * @param string $description
    *
    * @return Seo
    */
    public function setDescription($description = null)
    {
      if( null !== $description ){
        $this->description = TextParser::truncate($description, 150, true);
        $this->metadatas->set(
          'description',
          array(
            $this->createMetaName('description',$this->description),
            $this->createMetaProperty('og:description',$this->description),
            $this->createMetaProperty('twitter:description',$this->description),
          )
        );
      }
      return $this;
    }

    /**
    * @param string $image
    *
    * @return Seo
    */
    public function setImage($image = null)
    {
      if( null !== $image ){
        $this->image = $image;
        $this->metadatas->set(
          'image',
          array(
            $this->createMetaProperty('og:image',$this->image),
            $this->createMetaProperty('twitter:image',$this->image),
          )
        );
      }
      return $this;
    }


    /**
    *
    * @return ParameterBag
    */
    public function getMetadatas()
    {
      return $this->metadatas;
    }

    /**
    *
    * @param string $default
    *
    * @return string
    */
    public function getTitle($default = null)
    {
      if( 1 > count($this->titles)){
        return $default;
      }
      return implode(' | ', array_reverse($this->titles));
    }

    protected function createMetaName($name,$content){
      $meta = new ParameterBag();
      $meta->set('name', $name);
      $meta->set('content', $content);
      return $meta;
    }
    protected function createMetaProperty($name,$content){
      $meta = new ParameterBag();
      $meta->set('property', $name);
      $meta->set('content', $content);
      return $meta;
    }
}
