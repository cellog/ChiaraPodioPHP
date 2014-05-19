<?php
namespace Chiara\Iterators\PodioItemFilterIterator;
use Chiara\PodioApp as App, Chiara\Iterators\PodioItemFilterIterator\Field,
    Chiara\Iterators\PodioItemFilterIterator as Filter;
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
        if ($pseudo) {
            parent::__construct($this->pseudoMap);
            return;
        }
        $fields = $app->info;
        if (!isset($fields['fields'])) {
            $app->retrieve();
        }
        parent::__construct($app->info['fields']);
        foreach ($this->getArrayCopy() as $i => $field) {
            $this->map[$field['field_id']] = $this->map[$field['external_id']] = $i;
        }
    }

    function current()
    {
        $info = parent::current();
        if ($pseudo) {
            return $this->getPseudoField($this->key());
        }
        return Field::newField($this->app, $this->filter, $info);
    }

    function getPseudoField($index)
    {
        $class = $this->pseudomap[$index];
        Field::newPseudoField($this->app, $this->filter, $index, $class);
    }

    function offsetGet($index)
    {
        if ($this->pseudo) {
            return $this->getPseudoField($index);
        }
        if (is_int($index) && $index < 30) {
            $info = parent::offsetGet($index);
        } else {
            if (!isset($this->map[$index])) {
                throw new \Exception('Unknown field "' . $index . '"');
            }
            $info = parent::offsetGet($this->map[$index]);
        }
        return Field::newField($this->app, $this->filter, $info);
    }
}