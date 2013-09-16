<?php
/**
 * This file is part of the PrestaCMSCoreBundle.
 *
 * (c) PrestaConcept <www.prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Controller\Admin;

use Presta\CMSCoreBundle\Model\Block;
use Sonata\AdminBundle\Controller\CRUDController;

/**
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class BlockController extends CRUDController
{
    /**
     * @return WebsiteManager
     */
    protected function getWebsiteManager()
    {
        return $this->get('presta_cms.manager.website');
    }

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
     * This is called with blockId fron a container and with a zoneId from a zone
     *
     * @param  integer  $id
     * @return Response
     */
    public function addAction()
    {
        $zoneId     = $this->getRequest()->get('zoneId');
        $blockId    = $this->getRequest()->get('blockId');

        if ($this->get('request')->getMethod() == 'POST') {

            $block = $this->create($zoneId, $blockId);

            if ($this->isXmlHttpRequest()) {
                return $this->renderJson(
                    array(
                        'result'    => 'ok',
                        'action'    => 'add',
                        'zone'      => $zoneId,
                        'objectId'  => $block->getId(),
                        'content'   => $this->renderView(
                            'PrestaCMSCoreBundle:Admin/Block:_content_block.html.twig',
                            array('block' => $block)
                        )
                    )
                );
            }

            // redirect to edit mode
            return $this->redirectTo($block);
        }

        return $this->render(
            'PrestaCMSCoreBundle:Admin/Block:add_block.html.twig',
            array(
                'zoneId'    => $zoneId,
                'blockId'   => $blockId,
                'locale'    => $this->getRequest()->get('locale'),
                'blocks'    => $this->get('presta_cms.manager.block')->getBlocks()
            )
        );
    }

    /**
     * Create a new block
     *
     * @param $zoneId
     * @param $blockId
     * @return Block
     */
    protected function create($zoneId, $blockId)
    {
        $website = $this->getWebsiteManager()->getCurrentWebsite();
        $zoneFactory = $this->get('presta_cms.zone.factory');

        $blockConfiguration = array(
            'position'  => null,
            'type'      => $this->getRequest()->get('block')
        );

        if ($zoneId != null) {
            $zone = $zoneFactory->create(
                array(
                    'website'   => $website,
                    'id'        => $zoneId,
                    'blocks'    => array($blockConfiguration)
                )
            );
            $block = $zone->getBlocks()->last();
        } else {
            //Add a block in container case
            $blockConfiguration['id'] = $blockId;
            $block = $zoneFactory->createBlock($blockConfiguration, null, null, $website);
        }

        $zoneFactory->flush();
        $block->setAdminMode();

        return $block;
    }

    /**
     * Delete a block
     *
     * @return Response
     */
    public function deleteAction($id = null)
    {
        if ($this->get('request')->getMethod() == 'POST') {
            $id     = $this->getRequest()->get('id');
            $block  = $this->admin->getObject($id);

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
                        'content'   => $this->renderView(
                            'PrestaCMSCoreBundle:Admin/Block:delete_block_content.html.twig',
                            array('block' => $block)
                        ),
                    )
                );
            }

            return new RedirectResponse($this->admin->generateUrl('list'));
        }
    }
}
