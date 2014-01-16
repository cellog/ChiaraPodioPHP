<?php
include dirname(__DIR__) . '/autoload.php';

// format {"client":"your-client-id","token":"your api token"}
$config = json_decode(file_get_contents(__DIR__ . '/api.json'), 1);

//Podio::setup($config['client'], $config['token']);
//Podio::authenticate_with_app(6686618, '6b236efd6920431687b139fddab701e2');

//$app = new Chiara\PodioApp(6686618);
//$app->dump();

$app = new Chiara\PodioApp(
array (
  'status' => 'active',
  'subscribed' => false,
  'original_revision' => NULL,
  'rights' => 
  array (
    0 => 'add_hook',
    1 => 'add_item',
    2 => 'add_task',
    3 => 'subscribe',
    4 => 'view_structure',
    5 => 'view',
    6 => 'add_filter',
  ),
  'url' => 'https://podio.com/chiaraquartetnet/chiarapodio/apps/testing',
  'fields' => 
  array (
    0 => 
    array (
      'status' => 'active',
      'type' => 'text',
      'field_id' => 51928207,
      'label' => 'Title',
      'config' => 
      array (
        'default_value' => NULL,
        'description' => NULL,
        'settings' => 
        array (
          'size' => 'small',
        ),
        'required' => false,
        'mapping' => NULL,
        'label' => 'Title',
        'visible' => true,
        'delta' => 0,
        'hidden' => false,
      ),
      'external_id' => 'title',
    ),
    1 => 
    array (
      'status' => 'active',
      'type' => 'contact',
      'field_id' => 51928208,
      'label' => 'Contact',
      'config' => 
      array (
        'default_value' => NULL,
        'description' => NULL,
        'settings' => 
        array (
          'type' => 'space_users',
          'valid_types' => 
          array (
            0 => 'user',
          ),
        ),
        'required' => false,
        'mapping' => NULL,
        'label' => 'Contact',
        'visible' => true,
        'delta' => 1,
        'hidden' => false,
      ),
      'external_id' => 'contact',
    ),
    2 => 
    array (
      'status' => 'active',
      'type' => 'contact',
      'field_id' => 51928209,
      'label' => 'Contact',
      'config' => 
      array (
        'default_value' => NULL,
        'description' => NULL,
        'settings' => 
        array (
          'type' => 'space_contacts',
          'valid_types' => 
          array (
            0 => 'space',
          ),
        ),
        'required' => false,
        'mapping' => NULL,
        'label' => 'Contact',
        'visible' => true,
        'delta' => 2,
        'hidden' => false,
      ),
      'external_id' => 'contact-2',
    ),
    3 => 
    array (
      'status' => 'active',
      'type' => 'category',
      'field_id' => 51928210,
      'label' => 'Category',
      'config' => 
      array (
        'default_value' => NULL,
        'description' => NULL,
        'settings' => 
        array (
          'multiple' => false,
          'options' => 
          array (
          ),
          'display' => 'inline',
        ),
        'required' => false,
        'mapping' => NULL,
        'label' => 'Category',
        'visible' => true,
        'delta' => 3,
        'hidden' => false,
      ),
      'external_id' => 'category',
    ),
    4 => 
    array (
      'status' => 'active',
      'type' => 'date',
      'field_id' => 51928211,
      'label' => 'Date',
      'config' => 
      array (
        'default_value' => NULL,
        'description' => NULL,
        'settings' => 
        array (
          'calendar' => true,
        ),
        'required' => false,
        'mapping' => NULL,
        'label' => 'Date',
        'visible' => true,
        'delta' => 4,
        'hidden' => false,
      ),
      'external_id' => 'date',
    ),
    5 => 
    array (
      'status' => 'active',
      'type' => 'embed',
      'field_id' => 51928212,
      'label' => 'Link',
      'config' => 
      array (
        'default_value' => NULL,
        'description' => NULL,
        'settings' => NULL,
        'required' => false,
        'mapping' => NULL,
        'label' => 'Link',
        'visible' => true,
        'delta' => 5,
        'hidden' => false,
      ),
      'external_id' => 'link',
    ),
    6 => 
    array (
      'status' => 'active',
      'type' => 'image',
      'field_id' => 51928213,
      'label' => 'Image',
      'config' => 
      array (
        'default_value' => NULL,
        'description' => 'Supported image types: .jpg .gif .png .bmp',
        'settings' => 
        array (
          'allowed_mimetypes' => 
          array (
            0 => 'image/png',
          ),
        ),
        'required' => false,
        'mapping' => NULL,
        'label' => 'Image',
        'visible' => true,
        'delta' => 6,
        'hidden' => false,
      ),
      'external_id' => 'image',
    ),
    7 => 
    array (
      'status' => 'active',
      'type' => 'location',
      'field_id' => 51928214,
      'label' => 'Google Maps',
      'config' => 
      array (
        'default_value' => NULL,
        'description' => NULL,
        'settings' => NULL,
        'required' => false,
        'mapping' => NULL,
        'label' => 'Google Maps',
        'visible' => true,
        'delta' => 7,
        'hidden' => false,
      ),
      'external_id' => 'google-maps',
    ),
    8 => 
    array (
      'status' => 'active',
      'type' => 'question',
      'field_id' => 51928215,
      'label' => 'Question',
      'config' => 
      array (
        'default_value' => NULL,
        'description' => NULL,
        'settings' => 
        array (
          'multiple' => false,
          'options' => 
          array (
          ),
          'display' => 'inline',
        ),
        'required' => false,
        'mapping' => NULL,
        'label' => 'Question',
        'visible' => true,
        'delta' => 8,
        'hidden' => false,
      ),
      'external_id' => 'question',
    ),
    9 => 
    array (
      'status' => 'active',
      'type' => 'number',
      'field_id' => 51928216,
      'label' => 'Number',
      'config' => 
      array (
        'default_value' => NULL,
        'description' => NULL,
        'settings' => 
        array (
          'decimals' => NULL,
        ),
        'required' => false,
        'mapping' => NULL,
        'label' => 'Number',
        'visible' => true,
        'delta' => 9,
        'hidden' => false,
      ),
      'external_id' => 'number',
    ),
    10 => 
    array (
      'status' => 'active',
      'type' => 'money',
      'field_id' => 51928217,
      'label' => 'Money',
      'config' => 
      array (
        'default_value' => NULL,
        'description' => NULL,
        'settings' => 
        array (
          'allowed_currencies' => 
          array (
            0 => 'EUR',
            1 => 'DKK',
            2 => 'USD',
          ),
        ),
        'required' => false,
        'mapping' => NULL,
        'label' => 'Money',
        'visible' => true,
        'delta' => 10,
        'hidden' => false,
      ),
      'external_id' => 'money',
    ),
    11 => 
    array (
      'status' => 'active',
      'type' => 'duration',
      'field_id' => 51928218,
      'label' => 'Duration',
      'config' => 
      array (
        'default_value' => NULL,
        'description' => NULL,
        'settings' => NULL,
        'required' => false,
        'mapping' => NULL,
        'label' => 'Duration',
        'visible' => true,
        'delta' => 11,
        'hidden' => false,
      ),
      'external_id' => 'duration',
    ),
    12 => 
    array (
      'status' => 'active',
      'type' => 'progress',
      'field_id' => 51928219,
      'label' => 'Progress slider',
      'config' => 
      array (
        'default_value' => NULL,
        'description' => NULL,
        'settings' => NULL,
        'required' => false,
        'mapping' => NULL,
        'label' => 'Progress slider',
        'visible' => true,
        'delta' => 12,
        'hidden' => false,
      ),
      'external_id' => 'progress-slider',
    ),
    13 => 
    array (
      'status' => 'active',
      'type' => 'calculation',
      'field_id' => 51928244,
      'label' => 'Calculation',
      'config' => 
      array (
        'default_value' => NULL,
        'description' => NULL,
        'settings' => 
        array (
          'expression' => 
          array (
            'right' => 
            array (
              'field_id' => 51928216,
              'type' => 'field',
            ),
            'type' => 'plus',
            'left' => 
            array (
              'field_id' => 51928217,
              'type' => 'field',
            ),
          ),
          'unit' => '',
        ),
        'required' => false,
        'mapping' => NULL,
        'label' => 'Calculation',
        'visible' => true,
        'delta' => 13,
        'hidden' => false,
      ),
      'external_id' => 'calculation',
    ),
    14 => 
    array (
      'status' => 'active',
      'type' => 'app',
      'field_id' => 51928220,
      'label' => 'App reference',
      'config' => 
      array (
        'default_value' => NULL,
        'description' => NULL,
        'settings' => 
        array (
          'referenceable_types' => 
          array (
            0 => 6686618,
          ),
        ),
        'required' => false,
        'mapping' => NULL,
        'label' => 'App reference',
        'visible' => true,
        'delta' => 14,
        'hidden' => false,
      ),
      'external_id' => 'app-reference',
    ),
  ),
  'space_id' => 1802053,
  'url_add' => 'https://podio.com/chiaraquartetnet/chiarapodio/apps/testing/items/new',
  'link_add' => 'https://podio.com/chiaraquartetnet/chiarapodio/apps/testing/items/new',
  'app_id' => 6686618,
  'integration' => NULL,
  'pinned' => false,
  'token' => NULL,
  'link' => 'https://podio.com/chiaraquartetnet/chiarapodio/apps/testing',
  'url_label' => 'testing',
  'owner' => 
  array (
    'user_id' => 1834391,
    'space_id' => NULL,
    'image' => 
    array (
      'hosted_by' => 'podio',
      'hosted_by_humanized_name' => 'Podio',
      'thumbnail_link' => 'https://d3szoh0f46td6t.cloudfront.net/public/65246588',
      'link' => 'https://d3szoh0f46td6t.cloudfront.net/public/65246588',
      'file_id' => 65246588,
      'link_target' => '_blank',
    ),
    'profile_id' => 98265149,
    'org_id' => NULL,
    'link' => 'https://podio.com/users/1834391',
    'avatar' => 65246588,
    'type' => 'user',
    'last_seen_on' => '2014-01-16 16:59:22',
    'name' => 'Gregory Beaver',
  ),
  'mailbox' => NULL,
  'config' => 
  array (
    'allow_edit' => true,
    'tasks' => 
    array (
    ),
    'yesno' => false,
    'silent_creates' => false,
    'yesno_label' => NULL,
    'thumbs' => false,
    'app_item_id_padding' => 1,
    'show_app_item_id' => false,
    'default_view' => 'badge',
    'item_name' => 'thing',
    'allow_attachments' => true,
    'allow_create' => true,
    'app_item_id_prefix' => NULL,
    'disable_notifications' => false,
    'fivestar' => false,
    'thumbs_label' => NULL,
    'type' => 'standard',
    'rsvp' => false,
    'description' => '',
    'usage' => '',
    'fivestar_label' => NULL,
    'approved' => false,
    'icon' => '251.png',
    'allow_comments' => true,
    'name' => 'Testing',
    'icon_id' => 251,
    'silent_edits' => false,
    'rsvp_label' => NULL,
    'external_id' => NULL,
  ),
  'original' => NULL,
));

$structure = new Chiara\PodioApplicationStructure;
$structure->structureFromApp($app);
$structure->dump();