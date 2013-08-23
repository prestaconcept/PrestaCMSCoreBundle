<?php
/**
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
$loader->import(CMF_TEST_CONFIG_DIR . '/default.php');
$loader->import(CMF_TEST_CONFIG_DIR . '/phpcr_odm.php');
$loader->import(CMF_TEST_CONFIG_DIR . '/doctrine_orm.php');
$loader->import(CMF_TEST_CONFIG_DIR . '/sonata_admin.php');
$loader->import(__DIR__ . '/dist/prestacmscore.yml');
$loader->import(__DIR__ . '/dist/security.yml');