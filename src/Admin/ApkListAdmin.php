<?php

namespace App\Admin;

use App\Catrobat\Services\ScreenshotRepository;
use App\Entity\Program;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 * Class ApkListAdmin.
 */
class ApkListAdmin extends AbstractAdmin
{
  /**
   * @var string
   */
  protected $baseRouteName = 'admin_catrobat_apk_list';

  /**
   * @var string
   */
  protected $baseRoutePattern = 'apk_list';

  /**
   * @var array
   */
  protected $datagridValues = [
    '_sort_by' => 'apk_request_time',
  ];

  /**
   * @var ScreenshotRepository
   */
  private $screenshot_repository;

  /**
   * ApkListAdmin constructor.
   *
   * @param $code
   * @param $class
   * @param $baseControllerName
   */
  public function __construct($code, $class, $baseControllerName, ScreenshotRepository $screenshot_repository)
  {
    parent::__construct($code, $class, $baseControllerName);
    $this->screenshot_repository = $screenshot_repository;
  }

  /**
   * @param $object
   *
   * @return string
   */
  public function getThumbnailImageUrl($object)
  {
    /*
     * @var $object Program
     */
    return '/'.$this->screenshot_repository->getThumbnailWebPath($object->getId());
  }

  protected function configureQuery(ProxyQueryInterface $query): ProxyQueryInterface
  {
    $query = parent::configureQuery($query);
    $query->andWhere(
      $query->expr()->eq($query->getRootAliases()[0].'.apk_status', ':apk_status')
    );
    $query->setParameter('apk_status', Program::APK_READY);

    return $query;
  }

  /**
   * @param DatagridMapper $datagridMapper
   *
   * Fields to be shown on filter forms
   */
  protected function configureDatagridFilters(DatagridMapper $datagridMapper)
  {
    $datagridMapper
      ->add('id')
      ->add('name')
      ->add('user.username')
      ->add('apk_request_time')
    ;
  }

  /**
   * @param ListMapper $listMapper
   *
   * Fields to be shown on lists
   */
  protected function configureListFields(ListMapper $listMapper)
  {
    $listMapper
      ->addIdentifier('id')
      ->add('user', null, [
        'route' => [
          'name' => 'show',
        ],
      ])
      ->add('name')
      ->add('apk_request_time')
      ->add('thumbnail', 'string', ['template' => 'Admin/program_thumbnail_image_list.html.twig'])
      ->add('apk_status', ChoiceType::class, [
        'choices' => [
          Program::APK_NONE => 'none',
          Program::APK_PENDING => 'pending',
          Program::APK_READY => 'ready',
        ], ])
      ->add('_action', 'actions', [
        'actions' => [
          'Rebuild' => [
            'template' => 'Admin/CRUD/list__action_rebuild_apk.html.twig',
          ],
          'Delete Apk' => [
            'template' => 'Admin/CRUD/list__action_delete_apk.html.twig',
          ],
        ],
      ])
    ;
  }

  protected function configureRoutes(RouteCollection $collection)
  {
    $collection->clearExcept(['list']);
    $collection->add('rebuildApk', $this->getRouterIdParameter().'/rebuildApk');
    $collection->add('deleteApk', $this->getRouterIdParameter().'/deleteApk');
  }
}
