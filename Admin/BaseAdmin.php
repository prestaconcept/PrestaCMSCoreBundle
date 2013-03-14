<?php
/*
 * This file is part of the Presta Bundle project.
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Admin;

use Doctrine\ODM\PHPCR\DocumentManager;
use Sonata\DoctrinePHPCRAdminBundle\Admin\Admin as BasePHPCRAdmin;

/**
 * Base admin class for CMS
 */
abstract class BaseAdmin extends BasePHPCRAdmin
{
    /**
     * The translation domain to be used to translate messages
     *
     * @var string
     */
    protected $translationDomain = 'PrestaCMSCoreBundle';

    /**
     * @return DocumentManager
     */
    protected function getDocumentManager()
    {
        return $this->modelManager->getDocumentManager();
    }

    /**
     * Return current edition locale
     *
     * @return string
     */
    protected function getObjectLocale()
    {
        if ($this->request && $this->getRequest()->get('locale') != null) {
            return $this->getRequest()->get('locale');
        }

        return $this->getConfigurationPool()->getContainer()->getParameter('locale');
    }

    /**
     * {@inheritdoc}
     */
    public function generateUrl($name, array $parameters = array(), $absolute = false)
    {
        if ($name == 'create' || $name == 'edit') {
            $parameters = $parameters + array('locale' => $this->getObjectLocale());
        }

        return parent::generateUrl($name, $parameters, $absolute);
    }

    /**
     * Refresh object to load locale get in param
     *
     * @param   $id
     * @return  $subject
     */
    public function getObject($id)
    {
        $locale = $this->getObjectLocale();
        $object = $this->getDocumentManager()->findTranslation($this->getClass(), $id, $locale);

        //        if (!is_null($locale)) {
        //            //Here we have to consider the PHPCR fallback system and rest the locale
        //            //in case translation does not exist
        //            $this->getDocumentManager()->bindTranslation($object, $locale);
        //        }

        return $object;
    }
}
