<?php
namespace Chiara;
class PodioView
{
    protected $info;
    protected $appid;
    function __construct($appid, $viewid = null, $retrieve = true)
    {
        $this->appid = $appid;
        if (is_array($viewid)) {
            $this->info = $viewid;
            if ($retrieve !== 'force') return;
        } else {
            $this->info = array('view_id' => $viewid,
                                'name' => 'no name',
                                'sort_by' => 'created_on',
                                'filters' => array(),
                                'layout' => 'badge',
                                'fields' => array());
        }
        if (!$retrieve || !$this->id) return;
        $this->retrieve();
    }

    function retrieve()
    {
        Auth::prepareRemote($this->appid);
        $this->info = Remote::$remote->get('/view/app/' . $this->appid . '/' . $this->id)->json_body();
    }

    function __get($var) {
        if ($var == 'id') {
            return $this->info['view_id'];
        }
        return $this->info[$var];
    }

    protected function setField($name, $hidden, $x, $y, $width = null, $delta_offset = 0)
    {
        foreach ($this->fields as $i => $field) {
            if ($x && $field['use'] == 'x_axis') {
                unset($this->fields[$i]);
            }
            if ($y && $field['use'] == 'y_axis') {
                unset($this->fields[$i]);
            }
        }
        $this->fields[$name] = array(
            'delta_offset' => $delta_offset,
            'width' => $width,
            'hidden' => $hidden ? true : false,
            'use' => ($x ? 'use_x' : ($y ? 'use_y' : null))
        );
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