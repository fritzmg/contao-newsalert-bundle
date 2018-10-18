<?php

/*
 * Copyright (c) 2018 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0+
 */

namespace HeimrichHannot\ContaoNewsAlertBundle\EventListener;

use Contao\CoreBundle\Framework\ContaoFrameworkInterface;
use Contao\ModuleModel;
use Contao\PageModel;
use HeimrichHannot\ContaoNewsAlertBundle\Modules\NewsalertSubscribeModule;

class ModulesTableCallbackListener
{
    /**
     * @var ContaoFrameworkInterface
     */
    private $framework;

    public function __construct(ContaoFrameworkInterface $framework)
    {
        $this->framework = $framework;
    }

    /**
     * Returns a list of NewsalertSubscribeModules.
     *
     * @return array
     */
    public function getNewsalertModules()
    {
        $modules = $this->framework->getAdapter(ModuleModel::class)->findByType(NewsalertSubscribeModule::MODULE_NAME);
        if (!$modules) {
            return [];
        }
        $module_list = [];
        while ($modules->next()) {
            $module_list[$modules->id] = $modules->name;
        }

        return $module_list;
    }

    /**
     * Get dns adresses of root pages.
     *
     * @return array
     */
    public function getRootPagesWithDNS()
    {
        $rootPages = [];

        $pageModels = $this->framework->getAdapter(PageModel::class)->findPublishedRootPages();

        if (null === $pageModels) {
            return $rootPages;
        }

        while ($pageModels->next()) {
            if (!$pageModels->dns) {
                continue;
            }

            $rootPages[$pageModels->id] = $pageModels->dns.' [ID: '.$pageModels->id.']';
        }

        return $rootPages;
    }
}
