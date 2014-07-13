<?php

namespace Dson;

class Module
{
  /**
   * Setup ZF2 autoloading for this module
   * @return array
   */
  public function getAutoloaderConfig()
  {
    return array(
      'Zend\Loader\StandardAutoloader' => array(
        'namespaces' => array(
          __NAMESPACE__ => __DIR__ . '/src/' .  __NAMESPACE__,
        ),
      ),
    );
  }

  /**
   * Return configuration file for this module
   * @return mixed
   */
  public function getConfig()
  {
    return include __DIR__ . '/config/module.config.php';
  }
}