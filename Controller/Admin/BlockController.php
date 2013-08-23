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
use Presta\CMSCoreBundle\Doctrine\Phpcr\Block;
use Presta\CMSCoreBundle\Doctrine\Phpcr\Zone;

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
     * @param  integer  $id
     * @return Response
     */
    public function renderAction()
    {
        $id = $this->getRequest()->get('id');

        return $this->render('PrestaCMSCoreBundle:Admin/Block:render_block.html.twig', array('block' => $this->admin->getObject($id)));
    }

    /**
     * Add a block
     *
     * @param  integer  $id
     * @return Response
     */
    public function addAction()
    {
        $zoneId = $this->getRequest()->get('zoneId');
        $blockId = $this->getRequest()->get('blockId');
        $locale = $this->getRequest()->get('locale');

        if ($this->get('request')->getMethod() == 'POST') {
            $manager = $this->admin->getModelManager();
            $blockType = $this->getRequest()->get('block');

            if ($zoneId != null) {
                //Zone mode, eventually create the zone
                $zone = $manager->find('Presta\CMSCoreBundle\Doctrine\Phpcr\Zone', $zoneId);
                if (is_null($zone)) {
                    $zone = new Zone();
                    $zone->setId($zoneId);
                    $manager->create($zone);
                }
                $position = (count($zone->getBlocks()) + 1) * 10;
                $blockId = $zone->getId() . $blockType . '-' . $position;
            }

            //Create new block
            $block = new Block();
            $block->setId($blockId);
            $block->setLocale($locale);
            $block->setType($blockType);
            $block->setIsActive(true);
            $block->setIsDeletable(true);
            $block->setIsEditable(true);
            $block->setSettings(array());

            $manager->create($block);

            if ($this->isXmlHttpRequest()) {
                $block->setAdminMode();

                return $this->renderJson(
                    array(
                        'result' => 'ok',
                        'action' => 'add',
                        'zone' => $zoneId,
                        'objectId' => $block->getId(),
                        'content' => $this->renderView('PrestaCMSCoreBundle:Admin/Block:add_block_content.html.twig', array('block' => $block))
                    )
                );
            }
            // redirect to edit mode
            return $this->redirectTo($block);
        }

        $viewParams = array(
            'zoneId' => $zoneId,
            'blockId' => $blockId,
            'locale' => $locale,
            'blocks' => $this->get('presta_cms.manager.block')->getBlocks()
        );

        return $this->render('PrestaCMSCoreBundle:Admin/Block:add_block.html.twig', $viewParams);
    }

    /**
     * Delete a block
     *
     * @param  integer  $id
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
                return $this->renderJson(
                    array(
                        'result'    => 'ok',
                        'action'    => 'delete',
                        'zone'      => $block->getParent()->getId(),
                        'block'     => $block->getId(),
                        'content'   => $this->renderView('PrestaCMSCoreBundle:Admin/Block:delete_block_content.html.twig', array('block' => $block)),
                    )
                );
            }

            return new RedirectResponse($this->admin->generateUrl('list'));
        }
    }
}
