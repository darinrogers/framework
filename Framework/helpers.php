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
 * @param string $relativeUrl Relative URL
 * 
 * @return null
 */
function asset($relativeUrl)
{
    echo \Framework\Config::getInstance()->get('static-domain') . 
        $relativeUrl;
}