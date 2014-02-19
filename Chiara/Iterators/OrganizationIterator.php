<?php
namespace Chiara\Iterators;
use Chiara\PodioWorkspace as Workspace, Chiara\PodioOrganization as Org;
class OrganizationIterator extends \ArrayIterator
{
    protected $map = array();
    function __construct(array $orginfo)
    {
        parent::__construct($orginfo);
        foreach ($orginfo as $i => $org) {
            $this->map[$org['url_label']] = $this->map[$field['org_id']] = $i;
        }
    }

    function current()
    {
        $info = parent::current();
        return new Org($info);
    }

    function offsetGet($index)
    {
        if (is_int($index) && $index < 30) {
            $info = parent::offsetGet($index);
        } else {
            $info = parent::offsetGet($this->map[$index]);
        }
        return new Org($info);
    }
}