<?php
namespace Chiara;
class PodioItem
{
    protected $info = array();
    /**
     * The PodioApplicationStructure that defines this item's structure
     *
     * Note that fields may exist that are not in the definition (legacy deleted fields)
     * @var Chiara\PodioApplicationStructure
     */
    protected $app;

    function __construct(array $info = null, PodioApplicationStructure $structure = null)
    {
        if (!$structure) {
            $structure = new PodioApplicationStructure;
        }
        $this->app = $structure;
        $this->info = $info;
        if (!$retrieve || !$info || !isset($info['item_id'])) return;
        $this->retrieve();
    }

    function retrieve()
    {
        $this->info = Podio::get('/item/' . $this->info['item_id'])->json_body();
    }

    function __get($var)
    {
        if ($var == 'fields') {
            return new ItemFieldIterator($this);
        }
        if ($var == 'info') {
            return $this->info;
        }
        if ($var == 'id') {
            return $this->info['item_id'];
        }
    }

    function __set($var, $value)
    {
        if ($var == 'id') {
            $this->info['item_id'] = $value;
        }
        $this->info[$var] = $value;
    }

    function setFieldByIndex($index, $value)
    {
        $this->info['fields'][$index]['values'] = $value;
    }

    function dump()
    {
        var_export($this->info);
    }
}