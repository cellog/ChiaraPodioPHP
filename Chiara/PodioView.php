<?php
namespace Chiara;
use Chiara\PodioApp as App;
class PodioView
{
    protected $info;
    protected $app;
    function __construct(App $app, $viewid = null, $retrieve = true)
    {
        $this->app = $app;
        if (is_array($viewid)) {
            $this->info = $viewid;
            if ($retrieve !== 'force') return;
        } else {
            $this->info = array('view_id' => $viewid,
                                'name' => 'no name',
                                'sort_by' => 'created_on',
                                'sort_desc' => '1',
                                'filters' => array(),
                                'layout' => 'badge',
                                'fields' => array());
        }
        if (!$retrieve || !$this->id) return;
        $this->retrieve();
    }

    function badgeLayout()
    {
        $this->info['layout'] = 'badge';
        return $this;
    }

    function tableLayout()
    {
        $this->info['layout'] = 'table';
        $ret = new PodioView\Table($this->app, $this->viewid, false);
        $ret->fromView($this);
        return $ret;
    }

    function cardLayout()
    {
        $this->info['layout'] = 'card';
        $ret = new PodioView\Card($this->app, $this->viewid, false);
        $ret->fromView($this);
        return $ret;
    }

    function fromView(PodioView $clone)
    {
        $this->info = $clone->info;
    }

    function retrieve()
    {
        Auth::prepareRemote($this->app->id);
        $this->info = Remote::$remote->get('/view/app/' . $this->app->id . '/' . $this->id)->json_body();
        return $this;
    }

    function __get($var) {
        if ($var === 'id') {
            return $this->info['view_id'];
        }
        if ($var === 'info') {
            return $this->info;
        }
        if ($var === 'fields') {
            return new PodioItemIterator\Fields($this->app, $this);
        }
        if ($var === 'pseudofields') {
            return new PodioItemIterator\Fields($this->app, $this, true);
        }
        return $this->info[$var];
    }

    protected function setField($name, $hidden, $x, $y, $width = null, $delta_offset = 0)
    {
        foreach ($this->info['fields'] as $i => $field) {
            if ($x && $field['use'] == 'x_axis') {
                unset($this->info['fields'][$i]);
            }
            if ($y && $field['use'] == 'y_axis') {
                unset($this->info['fields'][$i]);
            }
        }
        $this->info['fields'][$name] = array(
            'delta_offset' => $delta_offset,
            'width' => $width,
            'hidden' => $hidden ? true : false,
            'use' => ($x ? 'use_x' : ($y ? 'use_y' : null))
        );
    }

    function setFilter($key, $values)
    {
        foreach ($this->info['filters'] as $i => $filter) {
            if ($filter['key'] == $key) {
                $this->info['filters'][$i]['values'] = $values;
                return;
            }
        }
        $this->info['filters'][] = array('key' => $key, 'values' => $values);
    }

    function sort($field, $desc = true)
    {
        $this->info['sort_by'] = $field;
        $this->info['sort_desc'] = $desc ? '1' : '0';
    }

    protected function getField($name)
    {
        return isset($this->fields[$name]) ? $this->fields[$name] : null;
    }

    function save()
    {
        $info = $this->info;
        if ($info['id']) {
            // update
        } else {
            $this->info['view_id'] = Remote::$remote->post('/view/app/' . $this->appid, $info)->json_body();
        }
    }
}