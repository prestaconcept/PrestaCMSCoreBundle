<?php
/**
 * This file is part of the PrestaCMSCoreBundle
 *
 * (c) PrestaConcept <www.prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Tests\Resources\DataFixtures\Phpcr;

use Doctrine\Common\Persistence\ObjectManager;
use PHPCR\Util\NodeHelper;
use Symfony\Cmf\Bundle\RoutingBundle\Model\Route;
use Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Phpcr\RedirectRoute;
use Symfony\Component\Yaml\Parser;
use Presta\CMSCoreBundle\DataFixtures\PHPCR\BaseRouteFixture;
use Presta\CMSCoreBundle\Doctrine\Phpcr\Website;

/**
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class LoadRoute extends BaseRouteFixture
{
    /**
     * Création des routes associées au contenu
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;
        $session = $manager->getPhpcrSession();

        //création namespace menu
        NodeHelper::createPath($session, '/website/sandbox/route');
        $root = $manager->find(null, '/website/sandbox/route');

        //Routing home
        $configuration = array(
            'parent' => $root,
            'content_path' => '/website/sandbox/page/home',
            'name' => 'en',
            'locale' => 'en'
        );
        $home = $this->getFactory()->create($configuration);
        $configuration['name'] = 'fr';
        $configuration['locale'] = 'fr';
        $homeFr = $this->getFactory()->create($configuration);

        $yaml = new Parser();
        $datas = $yaml->parse(file_get_contents(FIXTURES_DIR . 'pages.yml'));
        foreach ($datas['pages'] as $pageConfiguration) {
            if ($pageConfiguration['name'] == 'home') {
                continue;
            }
            $pageConfiguration['content_path'] = '/website/sandbox/page' . '/' .  $pageConfiguration['name'];
            $pageConfiguration['parent'] = $home;
            $pageConfiguration['locale'] = 'en';
            $route = $this->getFactory()->create($pageConfiguration);
            $this->generateRedirects($route, 'en');

            $pageConfiguration['parent'] = $homeFr;
            $pageConfiguration['locale'] = 'fr';
            $route = $this->getFactory()->create($pageConfiguration);
            $this->generateRedirects($route, 'fr');
        }

        $this->manager->flush();
    }

    /**
     * Generate dummy redirects for a given route
     *
     * @param Route $route
     */
    protected function generateRedirects(Route $route, $locale)
    {
        $rewrite = new RedirectRoute();
        $rewrite->setPosition($route->getParent(), $route->getName() . '-redirect-1');
        $rewrite->setDefault('_locale', $locale);
        $rewrite->setRequirement('_locale', $locale);
        $rewrite->setRouteTarget($route);
        $rewrite->setPermanent(true);

        $this->manager->persist($rewrite);

        $route = $this->manager->find(null, $route->getId());

        foreach ($route->getRouteChildren() as $child) {
            if ($child instanceof Route) {
                $this->generateRedirects($child, $locale);
            }
        }

        $rewrite = new RedirectRoute();
        $rewrite->setPosition($route->getParent(), $route->getName() . '-redirect-2');
        $rewrite->setDefault('_locale', $locale);
        $rewrite->setRequirement('_locale', $locale);
        $rewrite->setRouteTarget($route);
        $rewrite->setPermanent(true);

        $this->manager->persist($rewrite);

        $route = $this->manager->find(null, $route->getId());

        foreach ($route->getRouteChildren() as $child) {
            if ($child instanceof Route) {
                $this->generateRedirects($child, $locale);
            }
        }
    }
}
