<?php

namespace MinimalOriginal\CoreBundle\Twig;

use TT\Business\Doctrine\AdvertiseManager;
use TT\Business\Doctrine\DBAL\Types\EnumAdsFormatType;

class PaginationExtension extends \Twig_Extension
{
    /**
     * Returns a list of function to add to the existing list.
     *
     * @return array An array of functions
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('paginate', array($this, 'getPaginate')),
        );
    }

    /**
     * Returns array pages.
     *
     * @param integer $page
     * @param integer $limit
     * @param integer $total
     * @param integer $trigger
     *
     * @return array
     */
    public function getPaginate($page, $limit, $total, $trigger = 2)
    {
        $min  = 1;
        $max  = (int) ceil($total / max(1, $limit));
        $cur  = (int) $page;
        $prev = array();
        $next = array();

        if ($trigger + 2 === $cur) {
            $prev = array($min);
        } elseif ($trigger + 3 === $cur) {
            $prev = array($min, $min + 1);
        } elseif ($trigger + 4 <= $cur) {
            $prev = array($min, $min + 1, '...');
        }

        if ($max - $trigger - 2 === $cur) {
            $next = array($max - 1, $max);
        } elseif ($max - $trigger - 1 === $cur) {
            $next = array($max);
        } elseif ($max - $trigger - 3 >= $cur) {
            $next = array('...', $max - 1, $max);
        }

        $filterPage = function ($val) use ($min, $max) {
            if ('...' === $val) {
                return true;
            }

            return $val >= $min && $val <= $max;
        };

        return array_filter(array_merge($prev, range($cur - $trigger, $cur - 1), array($cur), range($cur + 1, $cur + $trigger), $next), $filterPage);
    }
}
