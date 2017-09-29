<?php

namespace MinimalOriginal\CoreBundle\Twig;

use MinimalOriginal\CoreBundle\Util\TextParser;

class TextExtension extends \Twig_Extension
{

    /**
     * Returns a list of filters to add to the existing list.
     *
     * @return array An array of filters
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('capitalize', array($this, 'capitalize'), array('needs_environment' => true)),
            new \Twig_SimpleFilter('truncate', array($this, 'getTruncate'), array('is_safe' => array('html'))),
            new \Twig_SimpleFilter('truncate_html', array($this, 'getTruncateHtml'), array('is_safe' => array('html'))),
            new \Twig_SimpleFilter('sanitize_external_url', array($this, 'sanitizeExternalUrl')),
            new \Twig_SimpleFilter('create_crypted_id', array($this, 'cryptedId')),
        );
    }

    /**
     * Override capitalize Twig native function.
     *
     * @param Twig_Environment $env    A Twig_Environment instance
     * @param string           $string A string
     *
     * @return string The capitalized string
     */
    public function capitalize(\Twig_Environment $env, $string)
    {
        if (null !== $charset = $env->getCharset()) {
            return mb_strtoupper(mb_substr($string, 0, 1, $charset), $charset) . mb_substr($string, 1, mb_strlen($string, $charset), $charset);
        }

        return ucfirst($string);
    }
    /**
     * To generate a crypted string like iframe url for advertising.
     *
     * @param string $string A string
     *
     * @return string
     */
    public function cryptedId($string)
    {
      $az = range('a', 'z');
      $AZ = range('A', 'Z');
      $numbers = range(0,9);

      $debut = array_rand(array_flip($az), rand(2,5));
      $end = array_rand(array_flip(array_merge($az,$AZ,$numbers)), rand(2,20));
      shuffle($debut);
      shuffle($end);

      return implode('', $debut) . implode('', $end);
    }

    /**
     * Returns text truncated.
     *
     * @param string  $value
     * @param integer $length
     * @param bool    $preserve
     * @param string  $separator
     *
     * @return string
     */
    public function getTruncate($value, $length = 30, $preserve = false, $separator = '...')
    {
        return TextParser::truncate($value, $length, $preserve, $separator);
    }

    /**
     * Returns text html truncated.
     *
     * @param string  $value
     * @param integer $length
     * @param string  $separator
     *
     * @return string
     */
    public function getTruncateHtml($value, $length = 100, $separator = '...')
    {
        return TextParser::truncateHtml($value, $length, $separator);
    }

    /**
     * Returns external url sanitize.
     *
     * @param string $url
     *
     * @return string
     */
    public function sanitizeExternalUrl($url)
    {
        return TextParser::sanitizeExternalUrl($url);
    }
}
