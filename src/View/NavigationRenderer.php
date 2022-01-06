<?php
/**
 * @see https://github.com/dotkernel/dot-navigation/ for the canonical source repository
 * @copyright Copyright (c) 2017 Apidemia (https://www.apidemia.com)
 * @license https://github.com/dotkernel/dot-navigation/blob/master/LICENSE.md MIT License
 */

declare(strict_types = 1);

namespace Dot\Navigation\View;

use Dot\Navigation\NavigationContainer;
use Dot\Navigation\Page;
use Dot\Navigation\Options\NavigationOptions;
use Dot\Navigation\Service\Navigation;
use Mezzio\Template\TemplateRendererInterface;

/**
 * Class NavigationRenderer
 * @package Dot\Navigation\View
 */
class NavigationRenderer extends AbstractNavigationRenderer
{
    /**
     * @var NavigationOptions
     */
    protected $options;

    /**
     * NavigationMenu constructor.
     * @param Navigation $navigation
     * @param TemplateRendererInterface $template
     * @param NavigationOptions $options
     */
    public function __construct(
        Navigation $navigation,
        TemplateRendererInterface $template,
        NavigationOptions $options
    ) {
        $this->options = $options;
        parent::__construct($navigation, $template);
    }

    /**
     * @param string $partial
     * @param string|NavigationContainer $container
     * @param array $params
     * @return string
     */
    public function renderPartial($container, string $partial, array $params = []): string
    {
        $container = $this->getContainer($container);

        return $this->template->render(
            $partial,
            array_merge(
                ['container' => $container, 'navigation' => $this->navigation],
                $params
            )
        );
    }

    /**
     * @param string|NavigationContainer $container
     * @return string
     */
    public function render($container): string
    {
        $container = $this->getContainer($container);
        $navListArray = $this->renderNavList($container);
        $template = "<!--<ul>-->".$navListArray['template']."<!--</ul>-->";


        // TODO: render a default HTML menu structure
        return $template;
    }

    private function renderNavList($container)
    {
        $template='';
        $ret=[];
        $isActiveStatus = false;
        foreach ($container as $page) {
            /* @var $page Page */
            $liClass="";
            if(!is_null($page->getOption('class'))){
                $liClass = $page->getOption('class');
            }
            if ($this->navigation->isActive($page)) {
                $liClass .= ' active';
                $isActiveStatus = true;
            }
            if($page->hasChildren())
            {
                $href=$page->getOption('uri');
            }
            else{
                $href = $this->navigation->getHref($page);
            }
            $target='';
            if(!is_null($page->getOption('target') ))
            {
                $target="target='{$page->getOption('target')}'";
            }

            if($page->hasChildren()){
                $navListArray = $this->renderNavList($page);
                if ($navListArray['isActive']){
                    $liClass = 'active';
                }
            }

            $template.="<li class='$liClass'><a href='$href' $target>{$page->getOption('label')}</a>";
            if($page->hasChildren()){
               $template.="<ul>{$navListArray['template']}</ul>";
            }
            $template.='</li>';
        }
        $ret['template'] = $template;
        $ret['isActive'] = $isActiveStatus;

        return $ret;
    }
}
