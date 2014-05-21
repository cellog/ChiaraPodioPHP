<?php
namespace Chiara\Iterators\PodioItemFilterIterator\PseudoFields;

class AuthClient extends IntegerList
{
    function podio()
    {
        $this->add(1);
    }

    function excelImport()
    {
        $this->add(57);
    }
}
