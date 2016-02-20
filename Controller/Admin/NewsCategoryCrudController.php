<?php

namespace Jarves\Publication\Controller\Admin;

class NewsCategoryCrudController extends \Jarves\Controller\ObjectCrudController  {

    public $fields = array (
  '__General__' => array (
    'label' => 'General',
    'type' => 'tab',
    'children' => array (
      'title' => array (
        'label' => 'Title',
        'options' => array (
          'modifier' => '',
        ),
        'type' => 'text',
      ),
    ),
  ),
);

    public $columns = array (
  'title' => array (
    'type' => 'text',
  ),
);

    public $itemLayout = '{{title}}';

    public $add = true;

    public $edit = true;

    public $remove = true;

    public $nestedRootAdd = false;

    public $nestedRootEdit = false;

    public $nestedRootRemove = false;

    public $export = false;

    public $object = 'jarvespublication/newsCategory';

    public $preview = false;

    public $titleField = 'title';

    public $workspace = false;

    public $multiLanguage = false;

    public $multiDomain = false;

    public $versioning = false;


}
