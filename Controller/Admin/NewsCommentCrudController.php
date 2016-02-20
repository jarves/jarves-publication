<?php

namespace Jarves\Publication\Controller\Admin;

class NewsCommentCrudController extends \Jarves\Controller\ObjectCrudController {

    public $fields = array (
  '__General__' => array (
    'label' => 'General',
    'type' => 'tab',
    'children' => array (
      'author' => array (
        'label' => 'Author',
        'type' => 'text',
      ),
      'message' => array (
        'label' => 'Message',
        'type' => 'textarea',
      ),
    ),
  ),
);

    public $columns = array (
  'id' => array (
    'label' => 'ID',
    'type' => 'text',
  ),
  'author' => array (
    'label' => 'Author',
    'type' => 'text',
  ),
  'created' => array (
    'label' => 'Created',
    'type' => 'datetime',
  ),
);

    public $itemLayout = '#{{id}} {{author}}<br />
<span class="sub">{{created}}</span>';

    public $add = true;

    public $edit = true;

    public $remove = true;

    public $nestedRootAdd = false;

    public $nestedRootEdit = false;

    public $nestedRootRemove = false;

    public $export = false;

    public $object = 'jarvespublication/newsComment';

    public $preview = false;

    public $workspace = false;

    public $multiLanguage = false;

    public $multiDomain = false;

    public $versioning = false;


}
