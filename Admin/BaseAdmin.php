<?php
/**
 * This file is part of the PrestaCMSCoreBundle
 *
 * (c) PrestaConcept <www.prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Admin;

use Doctrine\ODM\PHPCR\DocumentManager;
use Sonata\DoctrinePHPCRAdminBundle\Admin\Admin as BasePHPCRAdmin;

/**
 * Base admin class for CMS
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
abstract class BaseAdmin extends BasePHPCRAdmin
{
    /**
     * The translation domain to be used to translate messages
     *
     * @var string
     */
    protected $translationDomain = 'PrestaCMSCoreBundle';
}
