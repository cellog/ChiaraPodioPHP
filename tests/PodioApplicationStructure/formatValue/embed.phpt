--TEST--
PodioApplicationStructure->formatValue embed
--FILE--
<?php
include __DIR__ . '/../setup.php.inc';
include __DIR__ . '/structure.php.inc';
$structure = new Test;

$test->assertEquals(array(array('embed' => array('embed_id' => 3), 'file' => array('file_id' => 0))),
                    $structure->formatValue('link', 3), 'by external id');

$test->assertEquals(array(array('embed' => array('embed_id' => 0, 'url' => 'http://www.chiaraquartet.net'), 'file' => array('file_id' => 0))),
                    $structure->formatValue('link', 'http://www.chiaraquartet.net'), 'by external id');

$link = new Chiara\PodioEmbed;
$link->id = 2;
$link->file_id = 1;
$link->url = 'http://www.chiaraquartet.net';

$test->assertEquals(array(array('embed' => array('embed_id' => 2, 'url' => 'http://www.chiaraquartet.net'), 'file' => array('file_id' => 1))),
                    $structure->formatValue('link', $link), 'by external id');

$link2 = new Chiara\PodioEmbed;
$link2->id = 3;
class Foo extends Chiara\PodioItem\Field
{
}

$collection = new Chiara\PodioItem\Values\Collection(new Foo(new Chiara\PodioItem), array($link, $link2));

$test->assertEquals(array(
                          array('embed' => array('embed_id' => 2, 'url' => 'http://www.chiaraquartet.net'), 'file' => array('file_id' => 1)),
                          array('embed' => array('embed_id' => 3), 'file' => array()),
                    ),
                    $structure->formatValue('link', $collection), 'by external id');

echo "done\n";
?>
--EXPECT--
done
