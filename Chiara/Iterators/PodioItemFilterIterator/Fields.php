<?php
namespace Chiara\Iterators\PodioItemFilterIterator;
use Chiara\PodioApp as App, Chiara\Iterators\PodioItemFilterIterator\Field;
class Fields extends \ArrayIterator
{
    protected $app;
    protected $pseudo = false;
    protected $map = array();
    /**
     * Map of pseudo key to class that implements it
     */
    protected $pseudoMap = array(
        'created_by' => 'Auth',
        'created_via' => 'AuthClient',
        'last_edit_by' => 'Auth',
        'last_edit_via' => 'AuthClient',
        'created_on' => 'Date',
        'external_id' => 'IntegerList',
        'pinned' => 'TrueFalse',
        'title' => 'TrueFalse',
        'tags' => 'StringList',
        'like' => 'TrueFalse',
        'approved' => 'Rating',
        'rsvp' => 'Rating',
        'fivestar' => 'Rating',
        'yesno' => 'Rating',
        'thumbs' => 'Rating',
    );
    function __construct(App $app, Filter $filter, $pseudo = false)
    {
        $this->app = $app;
        $this->filter = $filter;
        $this->pseudo = $pseudo;
        parent::__construct($app->info['fields']);
        foreach ($this->getArrayCopy() as $i => $field) {
            $this->map[$field['field_id']] = $this->map[$field['external_id']] = $i;
        }
    }

    function current()
    {
        $info = parent::current();
        return Field::newField($this->app, $this->filter, $info);
    }

    function getPseudoField($index)
    {
        
    }

    function offsetGet($index)
    {
        if ($pseudo) {
            return $this->getPseudoField($index);
        }
        if (is_int($index) && $index < 30) {
            $info = parent::offsetGet($index);
        } else {
            $info = parent::offsetGet($this->map[$index]);
        }
        return Field::newField($this->app, $this->filter, $info);
    }
}