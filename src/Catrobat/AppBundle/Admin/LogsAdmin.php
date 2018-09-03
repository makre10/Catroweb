<?php

namespace Catrobat\AppBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Route\RouteCollection;

class LogsAdmin extends AbstractAdmin
{
  protected $baseRoutePattern = 'logs';
  protected $baseRouteName = 'logs';

  protected function configureRoutes(RouteCollection $collection)
  {
    $collection->clearExcept(['list']);
    $collection->add("apk")
      ->add("extracted")
      ->add("backup");
  }
}