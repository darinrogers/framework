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
    $md5 = 'v_' . md5((APP_DIR . $relativeUrl));
    
    $urlParts = explode('.', $relativeUrl);
    
    $numberOfParts = count($urlParts);
    $urlParts[] = $urlParts[$numberOfParts - 1];
    $urlParts[$numberOfParts - 1] = $md5;
    
    $relativeUrl = implode('.', $urlParts);
    
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