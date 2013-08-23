<?php
/**
 * This file is part of the Presta Bundle project.
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\DataFixtures\PHPCR;

use Presta\CMSCoreBundle\Doctrine\Phpcr\Page;
use Presta\CMSCoreBundle\Factory\PageFactory;

/**
 * Base fixtures methods to easily create pages
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
abstract class BasePageFixture extends BaseFixture
{
    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 20;
    }

    /**
     * @return PageFactory
     */
    public function getFactory()
    {
        return $this->container->get('presta_cms.page.factory');
    }
}
