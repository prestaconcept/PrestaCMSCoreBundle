<?php
/**
 * This file is part of the Presta Bundle project.
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Controller\Admin;

use Sonata\AdminBundle\Controller\CRUDController;
use Presta\CMSCoreBundle\Document\Block;

/**
 * Theme administration controller
 * 
 * @package    PrestaCMS
 * @subpackage CoreBundle
 * @author     Nicolas Bastien <nbastien@prestaconcept.net>
 */
class BlockController extends CRUDController
{
    /**
     * Render a block
     * 
     * @param  integer $id
     * @return Response 
     */
    public function renderAction()
    {
        $id = $this->getRequest()->get('id');

        return $this->render('PrestaCMSCoreBundle:Admin/Block:render_block.html.twig', array(
            'block' => $this->admin->getObject($id)
        ));
    }

    /**
     * Add a block
     *
     * @param  integer $id
     * @return Response
     */
    public function addAction()
    {
        $zoneId = $this->getRequest()->get('id');
        $locale = $this->getRequest()->get('locale');
        $origin = $this->getRequest()->get('origin');

        if ($this->get('request')->getMethod() == 'POST') {
            $manager = $this->admin->getModelManager();
            if ($origin == 'page') {
                $zoneClass = 'Presta\CMSCoreBundle\Document\Page\Zone';
            } else {
                $zoneClass = 'Presta\CMSCoreBundle\Document\Theme\Zone';
            }
            //ajout du block à la zone
            $zone = $manager->find($zoneClass, $zoneId);
            $position = (count($zone->getBlocks()) + 1) * 10;

            $blockType = $this->getRequest()->get('block');
            $block = new Block();
            $block->setName($blockType . '-' . $position);
            $block->setId($zone->getId().$block->getName());
            $block->setParent($zone);
            $block->setLocale($locale);
            $block->setType($blockType);
            $block->setIsActive(true);
            $block->setIsDeletable(true);
            $block->setIsEditable(true);
            $block->setSettings(array());

            $manager->create($block);

            if ($this->isXmlHttpRequest()) {
                return $this->renderJson(array(
                    'result'    => 'ok',
                    'action'    => 'add',
                    'zone'      => $zoneId,
                    'objectId'  => $block->getId(),
                    'content'   => $this->renderView('PrestaCMSCoreBundle:Admin/Block:add_block_content.html.twig', array('block' => $block))
                ));
            }
            // redirect to edit mode
            return $this->redirectTo($block);
        }

        //Temporaire
        //Il faudrait ajouter un tag au service de bloc + un group afin de faire directement le chargement par un compiler
        //faire un blockmanager
        $blocks = array (
            0 => 'presta_cms.block.simple',
            1 => 'presta_cms.block.page_children'
        );

        return $this->render('PrestaCMSCoreBundle:Admin/Block:add_block.html.twig', array(
            'zoneId' => $zoneId,
            'locale' => $locale,
            'blocks' => $blocks,
            'origin' => $origin
        ));
    }

    /**
     * Delete a block
     *
     * @param  integer $id
     * @return Response
     */
    public function deleteAction($id = null)
    {
        $id = $this->getRequest()->get('id');

        if ($this->get('request')->getMethod() == 'POST') {
            $block = $this->admin->getObject($id);

            if (!$block) {
                throw new NotFoundHttpException(sprintf('unable to find the object with id : %s', $id));
            }

            if (false === $this->admin->isGranted('DELETE', $block)) {
                throw new AccessDeniedException();
            }
            $this->admin->delete($block);

            if ($this->isXmlHttpRequest()) {
                return $this->renderJson(array(
                    'result'    => 'ok',
                    'action'    => 'delete',
                    'zone'      => $block->getParent()->getId(),
                    'block'  => $block->getId()
                ));
            }

            return new RedirectResponse($this->admin->generateUrl('list'));
        }

    }
}