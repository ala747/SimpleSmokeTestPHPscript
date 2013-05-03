#!/usr/local/bin/php
<?php
Class SmokeTest {

    private function collectURLs($dom, $tag, $attribute) {
        $collection = array();
        $anchors = $dom->getElementsByTagName($tag);
        foreach ($anchors as $element) {
            $href = $element->getAttribute($attribute);
            if (isset($href) && $href !== '' && 0 !== strpos($href, 'http')) {
                $path = '/' . ltrim($href, '/');
                if (extension_loaded('http')) {
                    $href = http_build_url($url, array('path' => $path));
                } else {
                    $parts = parse_url($url);
                    $href = $parts['scheme'] . '://';
                    if (isset($parts['user']) && isset($parts['pass'])) {
                        $href .= $parts['user'] . ':' . $parts['pass'] . '@';
                    }
                    $href .= $parts['host'];
                    if (isset($parts['port'])) {
                        $href .= ':' . $parts['port'];
                    }
                    $href .= $path;
                }
            }
            if (isset($href) && $href !== '')
                $collection[] = $href;
        }
        return $collection;
    }

    private function testThis($url, $verbose) {
        if ($verbose !== false)
            echo "Testing: ". $url ."\n";
        $status = exec("curl --silent --head '". $url ."' | head -1 | cut -f 2 -d' '");
        echo $status !== '' && $status < 400 ? "\033[0;32m  OK  \033[0m Status ". $status ."\n" : "\033[1;31m FAIL \033[0m Status ". $status ." for ". $url ."\n";
    }

    public function run($url = null, $include, $verbose = false) {
        
        if ($url === null)
            return;

        $this->testThis($url, $verbose);
        $dom = new DOMDocument('1.0');
        @$dom->loadHTMLFile($url);

        $scripts =  in_array('script', $include) ? $this->collectURLs($dom, 'script', 'src') : array();
        $links =  in_array('link', $include) ? $this->collectURLs($dom, 'link', 'href') : array();
        $imgs =  in_array('img', $include) ? $this->collectURLs($dom, 'img', 'src') : array();
        $collection = array_merge($scripts, $links, $imgs);

        foreach ($collection as $url) {
            $this->testThis($url, $verbose);
        }
    }
}

$arguments = getopt('u:i:vh');
if (!isset($arguments['u']) || isset($arguments['h']))
    die("Doc:\n\n-u <URL to test>       Required. The URL to check.\n-i [script|link|img]   Optional. HTML tag to look for child URLs to test. Specify one -i for each tag or none to collect all three.\n-v                     Optional. Verbose output\n-h                     Optional. Shows this help message\n\nUsage: ./smokeTest.php -u <URL to test> [-i script] [-i link] [-i img] [-v]\n\n");
$url = $arguments['u'];
if(isset($arguments['i'])){
    if(!is_array($arguments['i']))
        $include[] = $arguments['i'];
    else
        $include = $arguments['i'];
}

$argh = new SmokeTest;
$argh->run($url, isset($arguments['i']) ? $include : array('script', 'link', 'img'), isset($arguments['v']) ? true : false);