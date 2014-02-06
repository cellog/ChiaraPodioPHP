--TEST--
PodioApplicationStructure->structureFromItem basic test
--FILE--
<?php
include __DIR__ . '/../setup.php.inc';
$item = new Chiara\PodioItem(json_decode(file_get_contents(__DIR__ . '/item.json'), 1));
$structure = new Chiara\PodioApplicationStructure;
$structure->structureFromItem($item);
$test->assertEquals(array (
  51928207 => 
  array (
    'type' => 'text',
    'name' => 51928207,
    'id' => 'title',
    'config' => NULL,
  ),
  'title' => 
  array (
    'type' => 'text',
    'name' => 51928207,
    'id' => 'title',
    'config' => NULL,
  ),
  'contact' => 
  array (
    'type' => 'contact',
    'name' => 'contact',
    'id' => 51928208,
    'config' => 'space_users',
  ),
  51928208 => 
  array (
    'type' => 'contact',
    'name' => 'contact',
    'id' => 51928208,
    'config' => 'space_users',
  ),
  'contact-2' => 
  array (
    'type' => 'contact',
    'name' => 'contact-2',
    'id' => 51928209,
    'config' => 'space_contacts',
  ),
  51928209 => 
  array (
    'type' => 'contact',
    'name' => 'contact-2',
    'id' => 51928209,
    'config' => 'space_contacts',
  ),
  'category' => 
  array (
    'type' => 'category',
    'name' => 'category',
    'id' => 51928210,
    'config' => 
    array (
      'options' => 
      array (
        0 => 
        array (
          'status' => 'active',
          'text' => '1',
          'id' => 1,
          'color' => 'DCEBD8',
        ),
        1 => 
        array (
          'status' => 'active',
          'text' => '2',
          'id' => 2,
          'color' => 'DCEBD8',
        ),
      ),
      'multiple' => false,
    ),
  ),
  51928210 => 
  array (
    'type' => 'category',
    'name' => 'category',
    'id' => 51928210,
    'config' => 
    array (
      'options' => 
      array (
        0 => 
        array (
          'status' => 'active',
          'text' => '1',
          'id' => 1,
          'color' => 'DCEBD8',
        ),
        1 => 
        array (
          'status' => 'active',
          'text' => '2',
          'id' => 2,
          'color' => 'DCEBD8',
        ),
      ),
      'multiple' => false,
    ),
  ),
  51928211 => 
  array (
    'type' => 'date',
    'name' => 51928211,
    'id' => 'date',
    'config' => NULL,
  ),
  'date' => 
  array (
    'type' => 'date',
    'name' => 51928211,
    'id' => 'date',
    'config' => NULL,
  ),
  51928212 => 
  array (
    'type' => 'embed',
    'name' => 51928212,
    'id' => 'link',
    'config' => NULL,
  ),
  'link' => 
  array (
    'type' => 'embed',
    'name' => 51928212,
    'id' => 'link',
    'config' => NULL,
  ),
  51928213 => 
  array (
    'type' => 'image',
    'name' => 51928213,
    'id' => 'image',
    'config' => NULL,
  ),
  'image' => 
  array (
    'type' => 'image',
    'name' => 51928213,
    'id' => 'image',
    'config' => NULL,
  ),
  51928214 => 
  array (
    'type' => 'location',
    'name' => 51928214,
    'id' => 'google-maps',
    'config' => NULL,
  ),
  'google-maps' => 
  array (
    'type' => 'location',
    'name' => 51928214,
    'id' => 'google-maps',
    'config' => NULL,
  ),
  'question' => 
  array (
    'type' => 'question',
    'name' => 'question',
    'id' => 51928215,
    'config' => 
    array (
      'options' => 
      array (
        0 => 
        array (
          'status' => 'active',
          'text' => '3',
          'id' => 1,
          'color' => 'DCEBD8',
        ),
        1 => 
        array (
          'status' => 'active',
          'text' => '4',
          'id' => 2,
          'color' => 'DCEBD8',
        ),
      ),
      'multiple' => false,
    ),
  ),
  51928215 => 
  array (
    'type' => 'question',
    'name' => 'question',
    'id' => 51928215,
    'config' => 
    array (
      'options' => 
      array (
        0 => 
        array (
          'status' => 'active',
          'text' => '3',
          'id' => 1,
          'color' => 'DCEBD8',
        ),
        1 => 
        array (
          'status' => 'active',
          'text' => '4',
          'id' => 2,
          'color' => 'DCEBD8',
        ),
      ),
      'multiple' => false,
    ),
  ),
  51928216 => 
  array (
    'type' => 'number',
    'name' => 51928216,
    'id' => 'number',
    'config' => NULL,
  ),
  'number' => 
  array (
    'type' => 'number',
    'name' => 51928216,
    'id' => 'number',
    'config' => NULL,
  ),
  'money' => 
  array (
    'type' => 'money',
    'name' => 'money',
    'id' => 51928217,
    'config' => 
    array (
      0 => 'EUR',
      1 => 'DKK',
      2 => 'USD',
    ),
  ),
  51928217 => 
  array (
    'type' => 'money',
    'name' => 'money',
    'id' => 51928217,
    'config' => 
    array (
      0 => 'EUR',
      1 => 'DKK',
      2 => 'USD',
    ),
  ),
  51928218 => 
  array (
    'type' => 'duration',
    'name' => 51928218,
    'id' => 'duration',
    'config' => NULL,
  ),
  'duration' => 
  array (
    'type' => 'duration',
    'name' => 51928218,
    'id' => 'duration',
    'config' => NULL,
  ),
  51928219 => 
  array (
    'type' => 'progress',
    'name' => 51928219,
    'id' => 'progress-slider',
    'config' => NULL,
  ),
  'progress-slider' => 
  array (
    'type' => 'progress',
    'name' => 51928219,
    'id' => 'progress-slider',
    'config' => NULL,
  ),
  51928244 => 
  array (
    'type' => 'calculation',
    'name' => 51928244,
    'id' => 'calculation',
    'config' => NULL,
  ),
  'calculation' => 
  array (
    'type' => 'calculation',
    'name' => 51928244,
    'id' => 'calculation',
    'config' => NULL,
  ),
  'app-reference' => 
  array (
    'type' => 'app',
    'name' => 'app-reference',
    'id' => 51928220,
    'config' => 
    array (
      0 => 6686618,
    ),
  ),
  51928220 => 
  array (
    'type' => 'app',
    'name' => 'app-reference',
    'id' => 51928220,
    'config' => 
    array (
      0 => 6686618,
    ),
  ),
), $structure->getRawStructure(), 'output');
?>