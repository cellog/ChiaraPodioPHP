<?php
namespace Chiara;
use Podio, Chiara\Iterators\ItemFieldIterator;
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

    function __construct($info = null, PodioApplicationStructure $structure = null, $retrieve = true)
    {
        if (!$structure) {
            $structure = new PodioApplicationStructure;
        }
        $this->app = $structure;
        if (is_array($info) && $retrieve !== 'force') {
            $this->info = $info;
            return;
        }
        if (is_int($info)) {
            $info = array('item_id' => $info);
        }
        $this->info = $info;
        if (!$retrieve || !$info) return;
        $this->retrieve();
    }

    function retrieve()
    {
        if (!isset($this->info['item_id'])) {
            if (!isset($this->info['app_item_id'])) {
                // TODO: use custom exception
                throw new \Exception('Cannot retrieve item, no item_id or app_item_id');
            }
            $this->info = Podio::get('/app/' . $this->info['app']['app_id'] . '/item/' .
                                     $this->info['app_item_id'])->json_body();
        } else {
            $this->info = Podio::get('/item/' . $this->info['item_id'])->json_body();
        }
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

    function setFieldValue($index, $value)
    {
        $this->info['fields'][$index]['values'] = $value;
    }

    function dump()
    {
        var_export($this->info);
    }
}