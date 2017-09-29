<?php

namespace MinimalOriginal\CoreBundle\Util;

class TextParser
{
    /**
     * Returns content text on truncate and strip tag html.
     *
     * @param string  $content
     * @param integer $length
     * @param bool    $preserve
     * @param string  $separator
     *
     * @return string
     */
    static public function truncate($content, $length = 30, $preserve = false, $separator = '...')
    {
        $content = preg_replace(array('/ {2,}/', '/<!--.*?-->|\t|(?:\r?\n[ \t]*)+/s'), array(' ', ''), $content);
        $content = preg_replace('#<a.*?>.*?</a>#i', '', $content);
        $content = trim(strip_tags($content));


        if (mb_strlen($content, 'UTF-8') <= $length) {
            return $content;
        }

        if (true === $preserve) {
            if (false === ($breakpoint = mb_strpos($content, ' ', $length, 'UTF-8'))) {
                return $content;
            }

            $length = $breakpoint;
        }

        return rtrim(mb_substr($content, 0, $length, 'UTF-8')) . $separator;
    }

    /**
     * Returns content text on truncate and html.
     *
     * @param string  $content
     * @param integer $length
     * @param string  $separator
     *
     * @return string
     */
    static public function truncateHtml($content, $length = 100, $separator = '...')
    {

        if (mb_strlen(trim(strip_tags($content)), 'UTF-8') <= $length) {
            return $content;
        }

        preg_match_all('/(<.+?>)?([^<>]*)/s', $content, $lines, PREG_SET_ORDER);

        $totalLength = strlen($separator);
        $openTags = array();
        $content = '';

        foreach ($lines as $line) {
            if (false === empty($line[1])) {
                if (1 === preg_match('/^<(\s*.+?\/\s*|\s*(img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param)(\s.+?)?)>$/is', $line[1])) {
                    // do nothing
                } else if (1 === preg_match('/^<\s*\/([^\s]+?)\s*>$/s', $line[1], $tags)) {
                    if (false !== ($pos = array_search($tags[1], $openTags))) {
                        unset($openTags[$pos]);
                    }
                } else if (1 === preg_match('/^<\s*([^\s>!]+).*?>$/s', $line[1], $tags)) {
                    array_unshift($openTags, strtolower($tags[1]));
                }

                $content .= $line[1];
            }

            $contentLength = strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', ' ', $line[2]));

            if ($totalLength + $contentLength > $length) {
                $left = $length - $totalLength;
                $entityLength = 0;

                if (0 < preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', $line[2], $entities, PREG_OFFSET_CAPTURE)) {
                    foreach ($entities[0] as $entity) {
                        if ($entity[1] + 1 - $entityLength <= $left) {
                            $left--;
                            $entityLength += strlen($entity[0]);
                        } else {
                            break;
                        }
                    }
                }

                $content .= substr($line[2], 0, $left + $entityLength);

                break;
            } else {
                $content .= $line[2];
                $totalLength += $contentLength;
            }

            if ($totalLength >= $length) {
                break;
            }
        }

        if (false !== ($pos = strrpos($content, ' '))) {
            $content = substr($content, 0, $pos);
        }

        $content .= $separator;

        foreach ($openTags as $tag) {
            $content .= '</' . $tag . '>';
        }

        return $content;
    }

    /**
     * Returns external url sanitize.
     *
     * @param string $url
     *
     * @return string
     */
    static public function sanitizeExternalUrl($url)
    {
        if (0 === preg_match('~^(?:f|ht)tps?://~i', $url)) {
            return 'http://' . $url;
        }

        return $url;
    }
}
