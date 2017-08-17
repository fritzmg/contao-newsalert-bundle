<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2017 Heimrich & Hannot GmbH
 *
 * @author  Thomas Körner <t.koerner@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */

namespace HeimrichHannot\ContaoNewsAlertBundle\Forms;

use HeimrichHannot\ContaoNewsAlertBundle\Models\NewsalertRecipientsModel;
use HeimrichHannot\FormHybrid\Form;
use HeimrichHannot\NewsBundle\NewsModel;
use HeimrichHannot\StatusMessages\StatusMessage;

class NewsAlertSubscriptionForm extends Form
{
    protected $noEntity = true;
    protected $strMethod = FORMHYBRID_METHOD_POST;
    protected $strTemplate = 'formhybrid_default';
    protected $strTable = 'tl_newsalert_recipients';
    protected $strModelClass = NewsalertRecipientsModel::class;
    protected $isDuplicateEntityError = false;


    protected function compile()
    {
        if (!$this->Template->message)
        {
            $this->Template->message = StatusMessage::generate($this->objModule->id);
        }
    }

    protected function onSubmitCallback(\DataContainer $dc)
    {

        $strEmail = $dc->getFieldValue('email');
        $strTopic = $dc->getFieldValue('topic');

        $objRecipients = NewsalertRecipientsModel::findBy(
            ['email = ?', 'topic = ?'],
            [$strEmail, $strTopic]
        );

        if (!$objRecipients)
        {
            $this->objActiveRecord->email = $dc->getFieldValue('email');
            $this->objActiveRecord->topic = $dc->getFieldValue('topic');
            $this->objActiveRecord->dateAdded = time();
            $this->objActiveRecord->confirmed = 0;
            $this->objActiveRecord->save();
        }
        else
        {
            while ($objRecipients->next())
            {
                if (!$objRecipients->confirmed)
                {
                    $this->objActiveRecord = $objRecipients->current();
                    return;
                }
            }
            $this->isDuplicateEntityError = true;
        }
    }

    protected function createSubmission($strModelClass = null)
    {
        $strModelClass = $this->strModelClass;
        return parent::createSubmission($this->strModelClass);
    }
}