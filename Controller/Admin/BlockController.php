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
use Presta\CMSCoreBundle\Model\Zone;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\SecurityContextInterface;

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
     * @return SecurityContextInterface
     */
    protected function getSecurityContext()
    {
        return $this->get('security.context');
    }

    /**
     * Render a block
     *
     * @return Response
     */
    public function renderAction()
    {
        return $this->render(
            'PrestaCMSCoreBundle:Admin/Block:render_block.html.twig',
            array(
                'block' => $this->admin->getObject($this->getRequest()->get('id')),
            )
        );
    }

    /**
     * Add a block
     *
     * This is called with blockId fron a container and with a zoneId from a zone
     *
     * @throws AccessDeniedException
     *
     * @return Response
     */
    public function addAction()
    {
        if (!$this->getSecurityContext()->isGranted('ROLE_ADMIN_CMS_PAGE_STRUCTURE')) {
            throw new AccessDeniedException();
        }

        $zoneId     = $this->getRequest()->get('zoneId');
        $type       = $this->getRequest()->get('type');
        $blockId    = $this->getRequest()->get('blockId');

        if ($this->get('request')->getMethod() == 'POST') {
            return $this->create($zoneId, $blockId);
        }

        return $this->render(
            'PrestaCMSCoreBundle:Admin/Block:add_block.html.twig',
            array(
                'zoneId'    => $zoneId,
                'blockId'   => $blockId,
                'locale'    => $this->getRequest()->get('locale'),
                'blocks'    => $this->get('presta_cms.manager.block')->getBlocks($type),
            )
        );
    }

    /**
     * Create a new block
     *
     * @param  string   $zoneId
     * @param  string   $blockId
     * @return Response
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
            $blockConfiguration['id']       = $blockId;
            $blockConfiguration['website']  = $website;
            $block = $zoneFactory->createBlock($blockConfiguration);
        }

        $zoneFactory->flush();
        $block->setAdminMode();

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

    /**
     * @inheritdoc
     */
    public function deleteAction($id = null)
    {
        if (!$this->getSecurityContext()->isGranted('ROLE_ADMIN_CMS_PAGE_STRUCTURE')) {
            throw new AccessDeniedException();
        }

        if ($this->get('request')->getMethod() == 'POST') {
            $id     = $this->getRequest()->get('id');
            $block  = $this->admin->getObject($id);

            if (!$block) {
                throw new NotFoundHttpException(sprintf('unable to find the object with id : %s', $id));
            }
            if (false === $this->admin->isGranted('DELETE', $block)) {
                throw new AccessDeniedException();
            }

            return $this->delete($block);
        }
    }

    /**
     * Create a new block
     *
     * @param Block $block
     *
     * @return Response
     */
    protected function delete(Block $block)
    {
        $this->admin->delete($block);

        if ($this->isXmlHttpRequest()) {
            $data   = array('result' => 'ok', 'action' => 'delete', 'block' => $block->getId());
            $parent = $block->getParentDocument();

            if ($parent instanceof Zone) {
                $data['zone'] = $parent->getId();
            } else {
                $data['content'] = $this->renderView(
                    'PrestaCMSCoreBundle:Admin/Block:delete_block_content.html.twig',
                    array('block' => $block)
                );
            }

            return $this->renderJson($data);
        }

        return new RedirectResponse($this->admin->generateUrl('list'));
    }
}
