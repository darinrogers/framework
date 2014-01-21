<?php
/**
 * Helper functions
 *
 * PHP version 5.3
 * 
 * @category Framework
 * @package  Framework
 * @author   Darin Rogers <darinrogers@hotmail.com>
 * @license  http://opensource.org/licenses/bsd-license.php New BSD License
 * @link     https://github.com/darinrogers/framework
 */

const RETURN_NO_ECHO = true;

/**
 * Sets or gets page title
 * 
 * @param string $title Title
 * 
 * @return multitype:
 */
function title($title = '')
{
    if ($title != '') {
        
        \Framework\Layout::getInstance()->set('title', $title);
    
    } else {
        
        echo \Framework\Layout::getInstance()->get('title');
    }
}

/**
 * Adds a meta tag to the layout
 * 
 * @param string $name    Name
 * @param string $content Content
 * 
 * @return null
 */
function metaTag($name, $content)
{
    \Framework\Layout::getInstance()->addMetaTag($name, $content);
}

function styleSheet($href)
{
	\Framework\Layout::getInstance()->addStyleSheet($href);
}

/**
 * Echoes meta tags added with metaTag()
 * 
 * @return null
 */
function metaTags()
{
    $metaTags = \Framework\Layout::getInstance()->getMetaTags();
    
    foreach ($metaTags as $metaTag) {
        echo '<meta name="' . $metaTag[0] . '" content="' . $metaTag[1] . '">';
    }
}

function styleSheets()
{
	$styleSheets = \Framework\Layout::getInstance()->getStyleSheets();
	
	foreach ($styleSheets as $styleSheet) {
		echo '<link type="text/css" rel="stylesheet" href="' . $styleSheet . '">';
	}
}

/**
 * Echoes an asset absolute path
 * 
 * @param string $relativeUrl  Relative URL
 * @param bool   $returnNoEcho Returns the string instead of echoing
 * 
 * @return null|string
 */
function asset($relativeUrl, $returnNoEcho = false)
{
    $md5 = md5_file(((APP_DIR . '/public/' . $relativeUrl)));
    
    $urlParts = explode('.', $relativeUrl);
    
    $numberOfParts = count($urlParts);
    $urlParts[] = $urlParts[$numberOfParts - 1];
    $urlParts[$numberOfParts - 1] = 'v_' . $md5;
    
    $firstPart = implode('.', array_slice($urlParts, 0, count($urlParts) - 2));
    $secondPart = implode('.', array_slice($urlParts, count($urlParts) - 2, 2));
    
    $relativeUrl = implode('-', array($firstPart, $secondPart));
    
    $absoluteUrl = \Framework\Config::getInstance()->get('static-domain') . 
        $relativeUrl;
    
    if ($returnNoEcho) {
        
        return $absoluteUrl;
    
    } else {
        
        echo $absoluteUrl;
    }
}

/**
 * Writes out the javascript necessary to load a script asynchronously
 * 
 * @param string $absolutePath Absolute path to the script
 * 
 * @return string|null
 */
function asyncJs($absolutePath)
{
    echo '<script>' . 
        'var node = document.createElement(\'script\');' . 
        'node.type = \'text/javascript\';' . 
        'node.async = true;' . 
        'node.src = \'' . $absolutePath . '\';' . 
        'var s = document.getElementsByTagName(\'script\')[0]; ' . 
        's.parentNode.insertBefore(node, s);' . 
        '</script>';
}

function partial($partialName, array $parameters = array())
{
	foreach ($parameters as $name => $value) {
		$$name = $value;
	}
	
	include APP_DIR . '/view-partials/' . $partialName;
}