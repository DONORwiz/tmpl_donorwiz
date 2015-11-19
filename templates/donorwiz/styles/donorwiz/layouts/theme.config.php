<?php
/**
* @package   yoo_master2
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

/*
 * Generate 3-column layout
 */
 



$config          = $this['config'];
$sidebars        = $config->get('sidebars', array());
$columns         = array('main' => array('width' => 60, 'alignment' => 'right'));
$sidebar_classes = '';

$gcf = function($a, $b = 60) use(&$gcf) {
    return (int) ($b > 0 ? $gcf($b, $a % $b) : $a);
};

$fraction = function($nominator, $divider = 60) use(&$gcf) {
    return $nominator / ($factor = $gcf($nominator, $divider)) .'-'. $divider / $factor;
};

foreach ($sidebars as $name => $sidebar) {
	if (!$this['widgets']->count($name)) {
        unset($sidebars[$name]);
        continue;
    }

    $columns['main']['width'] -= @$sidebar['width'];
    $sidebar_classes .= " tm-{$name}-".@$sidebar['alignment'];
}

if ($count = count($sidebars)) {
	$sidebar_classes .= ' tm-sidebars-'.$count;
}

$columns += $sidebars;
foreach ($columns as $name => &$column) {

    $column['width']     = isset($column['width']) ? $column['width'] : 0;
    $column['alignment'] = isset($column['alignment']) ? $column['alignment'] : 'left';

    $shift = 0;
    foreach (($column['alignment'] == 'left' ? $columns : array_reverse($columns, true)) as $n => $col) {
        if ($name == $n) break;
        if (@$col['alignment'] != $column['alignment']) {
            $shift += @$col['width'];
        }
    }
    $column['class'] = sprintf('tm-%s uk-width-medium-%s%s', $name, $fraction($column['width']), $shift ? ' uk-'.($column['alignment'] == 'left' ? 'pull' : 'push').'-'.$fraction($shift) : '');
}

/*
 * Add grid classes
 */
$positions = array_keys($config->get('grid', array()));
$displays  = array('small', 'medium', 'large');
$grid_classes = array();
$display_classes = array();
foreach ($positions as $position) {

    $grid_classes[$position] = array();
    $grid_classes[$position][] = "tm-{$position} uk-grid";
    $display_classes[$position][] = '';

    if ($this['config']->get("grid.{$position}.divider", false)) {
        $grid_classes[$position][] = 'uk-grid-divider';
    }

    $widgets = $this['widgets']->load($position);

    foreach($displays as $display) {
        if (!array_filter($widgets, function($widget) use ($config, $display) { return (bool) $config->get("widgets.{$widget->id}.display.{$display}", true); })) {
            $display_classes[$position][] = "uk-hidden-{$display}";
        }
    }

    $display_classes[$position] = implode(" ", $display_classes[$position]);
    $grid_classes[$position] = implode(" ", $grid_classes[$position]);

}

/*
 * Add body classes
 */

$body_classes  = $sidebar_classes;
$body_classes .= $this['system']->isBlog() ? ' tm-isblog' : ' tm-noblog';
$body_classes .= ' '.$config->get('page_class');

$config->set('body_classes', trim($body_classes));

/*
 * Add social buttons
 */

$body_config = array();
$body_config['twitter']  = (int) $config->get('twitter', 0);
$body_config['plusone']  = (int) $config->get('plusone', 0);
$body_config['facebook'] = (int) $config->get('facebook', 0);
$body_config['style']    = $config->get('style');

$config->set('body_config', json_encode($body_config));

/*
 * Add assets
 */

// add css
$this['asset']->addFile('css', 'css:theme.css');
$this['asset']->addFile('css', 'css:custom.css');
$this['asset']->addFile('css', 'css:blog.css');


// add scripts
$this['asset']->addFile('js', 'js:uikit.js');
$this['asset']->addFile('js', 'warp:vendor/uikit/js/components/autocomplete.js');
$this['asset']->addFile('js', 'warp:vendor/uikit/js/components/search.js');
$this['asset']->addFile('js', 'warp:vendor/uikit/js/components/datepicker.js');
$this['asset']->addFile('js', 'warp:vendor/uikit/js/components/form-select.js');
$this['asset']->addFile('js', 'warp:vendor/uikit/js/components/upload.js');
$this['asset']->addFile('js', 'warp:vendor/uikit/js/components/timepicker.js');
$this['asset']->addFile('js', 'warp:vendor/uikit/js/components/notify.js');
$this['asset']->addFile('js', 'warp:vendor/uikit/js/components/sticky.js');
$this['asset']->addFile('js', 'warp:vendor/uikit/js/components/tooltip.js');

//add donorwiz scripts
$this['asset']->addFile('js', 'js:donorwiz/dw-wizard.js');
$this['asset']->addFile('js', 'js:donorwiz/maps/oms.min.js');
$this['asset']->addFile('js', 'js:donorwiz/maps/script.js');


$this['asset']->addFile('js', 'js:social.js');
$this['asset']->addFile('js', 'js:theme.js');

// internet explorer
if ($this['useragent']->browser() == 'msie') {
	
	
	$head[] = sprintf('<!--[if IE 8]><link rel="stylesheet" href="%s"><![endif]-->', $this['path']->url('css:ie8.css'));
    $head[] = sprintf('<!--[if lte IE 8]><script src="%s"></script><![endif]-->', $this['path']->url('js:html5.js'));
	$head[] = sprintf('<!--[if IE 9]><link rel="stylesheet" href="%s"><![endif]-->', $this['path']->url('css:ie9.css'));
    
}

//donorwiz
$head[] = sprintf("<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300&subset=latin,greek' rel='stylesheet' type='text/css'>");
$head[] = sprintf("<link href='http://fonts.googleapis.com/css?family=Open+Sans+Condensed:300&subset=latin,greek' rel='stylesheet' type='text/css'>");

if (isset($head)) {
	$this['template']->set('head', implode("\n", $head));
}

$document = JFactory::getDocument() ;
$document -> setGenerator('DONORwiz');

//Open graph
if( JFactory::getApplication() -> input -> get('option','','string')!='com_community')
{
	//Basic
	$document->addCustomTag('<meta property="og:title" content="'.$document->title.'" />' );
	$document->addCustomTag('<meta property="og:type" content="website" />' );
	$document->addCustomTag('<meta property="og:image" content="http://assets.donorwiz.com/logo/logo.png" />' );
	$document->addCustomTag('<meta property="og:url" content="'.JFactory::getURI()->toString().'" />' );
	//Optional
	$document->addCustomTag('<meta property="og:locale" content="'.str_replace ( '-' , '_' ,JFactory::getLanguage() ->getTag()  ).'" />' );
	$document->addCustomTag('<meta property="og:site_name" content="DONORwiz" />' );
}