<?php
namespace Chiara\Iterators;
use Chiara\PodioWorkspace as Workspace, Chiara\PodioOrganization as Org, Chiara\PodioApp,
    Chiara\Iterators\MatchingSpaceFilterIterator as Filter;
class WorkspaceIterator extends \ArrayIterator
{
    protected $workspace;
    protected $map = array();
    function __construct(Org $org, array $spaces)
    {
        $this->workspace = $org;
        parent::__construct($spaces);
        foreach ($this->getArrayCopy() as $i => $field) {
            $this->map[$field['name']] = $this->map[$field['url_label']] = $this->map[$field['space_id']] = $i;
        }
    }

    function matching($subportion)
    {
        $filter = new Filter($this, $subportion);
        return $filter;
    }

    function current()
    {
        $info = parent::current();
        return new Workspace($info['space_id']);
    }

    function rawCurrent()
    {
        return parent::current();
    }

    function offsetGet($index)
    {
        if (is_int($index) && $index < 300) {
            $info = parent::offsetGet($index);
        } else {
            $info = parent::offsetGet($this->map[$index]);
        }
        return new Workspace($info['space_id']);
    }
}