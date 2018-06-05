<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2018 Heimrich & Hannot GmbH
 *
 * @author  Thomas Körner <t.koerner@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */


namespace HeimrichHannot\ContaoNewsAlertBundle\EventListener;


use Contao\CoreBundle\Framework\ContaoFrameworkInterface;
use Contao\ModuleModel;
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
     * Returns a list of NewsalertSubscribeModules
     *
     * @return array
     */
    public function getNewsalertModules()
    {
        $modules = $this->framework->getAdapter(ModuleModel::class)->findByType(NewsalertSubscribeModule::MODULE_NAME);
        if (!$modules)
        {
            return [];
        }
        $module_list = [];
        while ($modules->next()) {
            $module_list[$modules->id] = $modules->name;
        }

        return $module_list;
    }

}