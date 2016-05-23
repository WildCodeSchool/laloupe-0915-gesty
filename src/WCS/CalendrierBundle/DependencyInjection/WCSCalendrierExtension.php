<?php
/*=============================================================================================

    SERVICE :
        wcs.calendrierscolaire

    INSTANCE RETOURNEE :
        WCS\CalendrierBundle\CalendrierScolaire\CalendrierScolaire

    DESCRIPTION :
        Ce service permet de récupérer un calendrier scolaire, déjà chargé avec
        ses périodes de vacances, de classe et les jours fériés,
        et ce pour n'importe quelle année donnée (du moins en fonction des années possibles)

    DATE DE CREATION :
        21 mai 2016

    DERNIERE MODIFICATION :
        22 mai 2016

    SOURCES :
        Le code a été rédigé à l'aide des sources suivantes :
        - http://symfony.com/doc/current/book/service_container.html
        - http://symfony.com/doc/current/cookbook/bundles/extension.html

    TESTS :
        Dans : WCS\CalendrierBundle\Tests

        Pour les exécuter directement :
            phpunit -c src/WCS/CalendrierBundle/

    --------------------------------------------------------------------------------------

    Ce service permet d'obtenir une instance de la classe "CalendrierScolaire"
    (dans WCS\CalendrierBundle\CalendrierScolaire).

    Pour connaitre l'utilisation de cette classe, aller dans
    WCS\CalendrierBundle\CalendrierScolaire\CalendrierScolaire.php


    Exemple d'utilisation :

    Dans app/app_kernel.php, dans la méthode registerBundles ajouter :

        new WCS\CalendrierBundle\WCSCalendrierBundle()

    Puis depuis n'importe quelle classe héritant de ContainerAware
    il sera possible de récupérer une instance de ce CalendrierScolaire avec :

        $calendrier = $this->container->get("wcs.calendrierscolaire");

    ou depuis une méthode de n'importe quel controlleur :

        $cal = $this->get("wcs.calendrier");



=============================================================================================*/

namespace WCS\CalendrierBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;


class WCSCalendrierExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load("services.yml");

        $this->addClassesToCompile(array(
            'WCS\CalendrierBundle\Service\Service'
            )
        );
    }
}