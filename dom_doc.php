<?php

/*
 * Dom Document Class
 */

class dom_doc {
    /*
     * Load DOM Document
     */

    public function load($html) {
        $html = file_get_contents($html);
        ob_start();
        ob_end_clean();
        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        $dom->strictErrorChecking = FALSE;

        if ($dom->loadHTML($html)) {
            libxml_clear_errors();
            return $dom;
        } else {
            return FALSE;
        }
        unset($html, $dom);
    }

    /*
     * Get All Meta Tags
     */

    public function metaTags($html) {
        $metaTags = get_meta_tags($html);
        if ($load = $this->load($html)) {

            $title = $load->getElementsByTagName('title');
            $metaTags['title'] = $title->item(0)->nodeValue;

            foreach ($load->getElementsByTagName('meta') as $node) {
                if (preg_match("@og:+([a-z-_])+@", $node->getAttribute('property'))) {
                    $metaTags[$node->getAttribute('property')] = $node->getAttribute('content');
                }
            }

            return $metaTags;
        }
        unset($html, $load, $node, $metaTags, $title);
    }

    /*
     * Get FavIcon
     */

    public function favicon($html) {
        $matches = '';
        if ($load = $this->load($html)) {
            foreach ($load->getElementsByTagName('link') as $node) {

                if ($node->getAttribute('rel') == 'icon' ||
                        $node->getAttribute('rel') == 'shortcut icon' ||
                        $node->getAttribute('rel') == 'Shortcut Icon') {

                    $matches = $node->getAttribute('href');
                    break;
                }
            }
            return $matches;
        }
        unset($html, $matches, $load, $node);
    }

    /*
     * Get `a` Tag Link
     */

    public function hrefs($html, $num = 'all') {
        $matches = array();
        if ($load = $this->load($html)) {
            foreach ($load->getElementsByTagName('a') as $node) {
                $matches[] = $node->getAttribute('href');
            }
            if ($num === 'all') {
                return $matches;
            } else {
                if (isset($matches[$num])) {
                    return $matches[$num];
                } else {
                    return FALSE;
                }
            }
        } else {
            return FALSE;
        }
        unset($html, $num, $load, $matches, $node);
    }

    /*
     * Get Script Tag Link
     */

    public function scripts($html, $num = 'all') {
        $matches = array();
        if ($load = $this->load($html)) {
            foreach ($load->getElementsByTagName('script') as $node) {
                $matches[] = $node->getAttribute('src');
            }
            if ($num === 'all') {
                return $matches;
            } else {
                if (isset($matches[$num])) {
                    return $matches[$num];
                } else {
                    return FALSE;
                }
            }
        } else {
            return FALSE;
        }
        unset($html, $num, $load, $matches, $node);
    }

    /*
     * Get Css Link
     */

    public function styles($html, $num = 'all') {
        $matches = '';
        if ($load = $this->load($html)) {
            foreach ($load->getElementsByTagName('link') as $node) {

                if ($node->getAttribute('rel') == 'stylesheet') {
                    $matches[] = $node->getAttribute('href');
                }
            }
            if ($num === 'all') {
                return $matches;
            } else {
                if (isset($matches[$num])) {
                    return $matches[$num];
                } else {
                    return FALSE;
                }
            }
        } else {
            return FALSE;
        }
        unset($html, $num, $load, $matches, $node);
    }

    function __destruct() {
        unset($this);
    }

}

?>