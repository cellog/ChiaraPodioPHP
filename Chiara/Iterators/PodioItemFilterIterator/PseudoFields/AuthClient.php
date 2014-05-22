<?php
namespace Chiara\Iterators\PodioItemFilterIterator\PseudoFields;

class AuthClient extends StringList
{
    protected function addInteger($item)
    {
        $this->filterinfo[] = (int) $item;
        $this->filterinfo = array_unique($this->filterinfo);
        $this->saveFilter();
        return $this;
    }

    function podio()
    {
        $this->addInteger(1);
        return $this;
    }

    function excelImport()
    {
        $this->addInteger(57);
        return $this;
    }

    function client($name)
    {
        $this->add($name);
        return $this;
    }
}
