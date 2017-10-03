<?php

namespace MinimalOriginal\CoreBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class SeoExtension extends \Twig_Extension implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
      return array(
          new \Twig_SimpleFunction('seo_title', array($this, 'getTitle'), array('is_safe' => array('html'))),
          new \Twig_SimpleFunction('seo_metadatas', array($this, 'getMetadatas'), array('is_safe' => array('html'))),
          new \Twig_SimpleFunction('seo_html_attributes', array($this, 'getHtmlAttributes'), array('is_safe' => array('html'))),
          new \Twig_SimpleFunction('seo_head_attributes', array($this, 'getHeadAttributes'), array('is_safe' => array('html'))),
          new \Twig_SimpleFunction('seo_link_canonical', array($this, 'getLinkCanonical'), array('is_safe' => array('html'))),
          new \Twig_SimpleFunction('seo_lang_alternates', array($this, 'getLangAlternates'), array('is_safe' => array('html'))),
          new \Twig_SimpleFunction('seo_oembed_links', array($this, 'getOembedLinks'), array('is_safe' => array('html'))),
      );
    }

    /**
     *
     * @param string $default
     *
     * @return string
     */
    public function getTitle($default = null)
    {
        $seo = $this->container->get('minimal_seo');
        return sprintf('<title>%s</title>', strip_tags($seo->getTitle($default)));
    }

   /**
     * @return string
     */
    public function getMetadatas()
    {
        $seo = $this->container->get('minimal_seo');

        $html = '';
        foreach($seo->getMetadatas()->all() as $type=>$metas ){
          foreach($metas as $meta ){
            if( $meta->has('property') ){
              $html .= sprintf("<meta property=\"%s\" content=\"%s\" />\n",
                  $meta->get('property'),
                  $meta->get('content')
              );
            }elseif( $meta->has('name') ){
              $html .= sprintf("<meta name=\"%s\" content=\"%s\" />\n",
                  $meta->get('name'),
                  $meta->get('content')
              );
            }
          }
        }

        return $html;
    }

    /**
     * @param string $string
     *
     * @return mixed
     */
    private function normalize($string)
    {
        $encoding = ini_get("default_charset");
        return htmlentities(strip_tags($string), ENT_COMPAT, $encoding);
    }
}
