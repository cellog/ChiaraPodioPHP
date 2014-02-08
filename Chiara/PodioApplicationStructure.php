<?php
namespace Chiara;
/**
 * this class is used to access podio applications, or as a blueprint for items to help when validating
 * changes to a podio item
 */
class PodioApplicationStructure
{
    const APPNAME = '';
    /**
     * Use this variable to define your application's structure offline
     *
     * The structure array is used to provide metadata about fields.  The important information is what
     * kind of field is associated with an external_id or a field id.  This allows easy validation and
     * retrieval of fields.
     */
    protected $structure = array();

    /**
     * A map of applications to their structures, useful for retrieving new objects
     */
    static private $structures = array();

    function __construct()
    {
        if (count($this->structure)) {
            if (!static::APPNAME) {
                // TODO: convert this to a Chiara-specific exception
                throw new \Exception('Error: the APPNAME constant must be overridden and set to the app\'s name');
            }
            self::$structures[static::APPNAME] = array($this->structure, get_class($this));
        } elseif (static::APPNAME && isset(self::$structures[static::APPNAME])) {
            $this->structure = self::$structures[static::APPNAME][0];
        }
    }

    /**
     * useful when constructing your application
     */
    function dumpStructure()
    {
        return var_export($this->structure, 1);
    }

    function getRawStructure()
    {
        return $this->structure;
    }

    function addTextField($name, $id)
    {
        $this->addField('text', $name, $id);
    }

    function addNumberField($name, $id)
    {
        $this->addField('number', $name, $id);
    }

    function addImageField($name, $id)
    {
        $this->addField('image', $name, $id);
    }

    function addDateField($name, $id)
    {
        $this->addField('date', $name, $id);
    }

    function addAppField($name, $id, array $referenceable_types)
    {
        $this->addField('app', $name, $id, $referenceable_types);
    }

    function addMoneyField($name, $id, array $allowed_currencies)
    {
        $this->addField('money', $name, $id, $allowed_currencies);
    }

    function addProgressField($name, $id)
    {
        $this->addField('progress', $name, $id);
    }

    function addLocationField($name, $id)
    {
        $this->addField('location', $name, $id);
    }

    function addDurationField($name, $id)
    {
        $this->addField('duration', $name, $id);
    }

    function addContactField($name, $id, $type)
    {
        if (!in_array($type, array('space_users', 'all_users', 'space_contacts', 'space_users_and_contacts'))) {
            // TODO: convert to custom Chiara exception
            throw new \Exception('Invalid type "' . $type . '" for contact field "' . $name . '"');
        }
        $this->addField('contact', $name, $id, $type);
    }

    function addCalculationField($name, $id)
    {
        $this->addField('calculation', $name, $id);
    }

    function addEmbedField($name, $id)
    {
        $this->addField('embed', $name, $id);
    }

    function addQuestionField($name, $id, array $options, $multiple)
    {
        $this->addMultipleChoiceField('question', $name, $id, $options, $multiple);
    }
    
    protected function addMultipleChoiceField($type, $name, $id, array $options, $multiple)
    {
        $options = array_values($options);
        foreach ($options as $option) {
            if (!is_array($option)) {
                throw new \Exception('Each option must be an array');
            }
            if (array_keys($option) != array('status', 'text', 'id', 'color')) {
                throw new \Exception('Invalid options, must be an array of arrays with keys "status", "text", "id", "color"');
            }
        }
        $this->addField($type, $name, $id, array('options' => $options, 'multiple' => $multiple));
    }

    function addCategoryField($name, $id, array $options, $multiple)
    {
        $this->addMultipleChoiceField('category', $name, $id, $options, $multiple);
    }

    /**
     * The "file" field type only exists in legacy Podio apps
     */
    function addFileField($name, $id)
    {
        $this->addField('file', $name, $id);
    }

    /**
     * The "video" field type only exists in legacy Podio apps
     */
    function addVideoField($name, $id)
    {
        $this->addField('video', $name, $id);
    }

    /**
     * The "state" field type only exists in legacy Podio apps
     */
    function addStateField($name, $id, array $allowed_values)
    {
        $this->addField('state', $name, $id, $allowed_values);
    }

    /**
     * The "media" field type only exists in legacy Podio apps
     */
    function addMediaField($name, $id)
    {
        $this->addField('media', $name, $id);
    }

    function addField($type, $name, $id, $config = null)
    {
        if (isset($this->structure[$name])) {
            throw new \Exception('Error: field with external-id "' . $name . '" already exists');
        }
        if (isset($this->structure[$id])) {
            throw new \Exception('Error: field with id "' . $id . '" already exists');
        }
        $this->structure[$name] = array('type' => $type, 'name' => $name, 'id' => $id, 'config' => $config);
        $this->structure[$id] = array('type' => $type, 'name' => $name, 'id' => $id, 'config' => $config);
    }

    static function fromItem(PodioItem $item)
    {
        $app = $item->app;
        $id = $app->space_id . '/' . $app->app_id;
        if (isset(self::$structures[$id])) {
            return self::$structures[$id];
        }
        $ret = new self;
        $ret->structureFromItem($item);
        return $ret;
    }

    /**
     * translate a Podio app downloaded from the API into a structure object
     */
    function structureFromApp(PodioApp $app)
    {
        return $this->genericStructure($app);
    }

    function structureFromItem(PodioItem $item)
    {
        return $this->genericStructure($item, false);
    }
    
    protected function genericStructure($app, $addToList = true)
    {
        foreach ($app->fields as $field) {
            switch ($field->type()) {
                case 'state' :
                    $this->addStateField($field->external_id, $field->id, $field->allowed_values);
                    break;
                case 'app' :
                    $this->addAppField($field->external_id, $field->id, $field->referenceable_types);
                    break;
                case 'money' :
                    $this->addMoneyField($field->external_id, $field->id, $field->allowed_currencies);
                    break;
                case 'contact' :
                    $this->addContactField($field->external_id, $field->id, $field->contact_type);
                    break;
                case 'question' :
                    $this->addQuestionField($field->external_id, $field->id, $field->options, $field->multiple);
                    break;
                case 'category' :
                    $this->addCategoryField($field->external_id, $field->id, $field->options, $field->multiple);
                    break;
                case 'text' :
                case 'number' :
                case 'image' :
                case 'media' :
                case 'date' :
                case 'progress' :
                case 'location' :
                case 'video' :
                case 'duration' :
                case 'calculation' :
                case 'embed' :
                case 'file' :
                default :
                    $this->addField($field->type, $field->id, $field->external_id);
                    break;
            }
        }
        if (!$addToList) return;
        self::$structures[$app->space_id . '/' . $app->app_id] = array($this->structure, get_class($this));
    }

    static function getStructure($appname, $strict = false, $overrideclassname = false)
    {
        if (!isset(self::$structures[$appname])) {
            if ($strict) {
                // TODO: convert this to a Chiara-specific exception
                throw new \Exception('No structure found for app "' . $appname . '"');
            }
            return new self;
        }
        $class = self::$structures[$appname][1];
        return new $class;
    }

    /**
     * Format a value for creating a new item
     */
    function formatValue($id, $value)
    {
        $type = $this->structure[$id]['type'];
        $structure = $this->structure[$id];
        $id_name = null;
        switch ($type) {
            case 'state' :
            case 'media' :
            case 'video' :
            case 'file' :
                break;
            case 'app' :
                $idname = 'app_id';
            case 'contact' :
                if (!isset($idname)) {
                    $idname = 'profile_id';
                }
                if (is_int($value)) {
                    $value = array(array('value' => array($idname => $value)));
                } elseif (is_array($value)) {
                    $newvalue = array();
                    foreach ($value as $a => $b) {
                        if (is_int($b)) {
                            $b = array($idname => $b);
                        } elseif (is_object($b)) {
                            $b = array($idname => $b->id);
                        }
                        $newvalue[$a] = array('value' => $b);
                    }
                    $value = $newvalue;
                } elseif ($value instanceof PodioItem\Values\Collection) {
                    $newvalue = array();
                    foreach ($value as $obj) {
                        $newvalue[] = array('value' => array($idname => $obj->id));
                    }
                    $value = $newvalue;
                } elseif (is_object($value)) {
                    $value = array(array('value' => $value->toArray()));
                }
                break;
            case 'embed' :
                if (is_int($value)) {
                    $value = array(array('embed' => array('embed_id' => $value), 'file' => array('file_id' => 0)));
                } elseif (is_string($value)) {
                    $value = array(array('embed' => array('embed_id' => 0, 'url' => $value), 'file' => array('file_id' => 0)));
                } elseif (is_array($value) || $value instanceof PodioItem\Values\Collection) {
                    if (isset($value['embed'])) {
                        $value = array($value);
                    } else {
                        $newvalue = array();
                        foreach ($value as $obj) {
                            $newvalue[] = $obj->toArray();
                        }
                        $value = $newvalue;
                    }
                } elseif (is_object($value)) {
                    $value = array($value->toArray());
                }
                break;
            case 'date' :
                if (is_string($value)) {
                    $value = strtotime($value);
                    $value = array('start' => date('Y-m-d H:i:s', $value));
                } elseif (is_int($value)) {
                    $value = array('start' => date('Y-m-d H:i:s', $value));
                } elseif (is_array($value)) {
                    if (count($value) == 2 && isset($value['start']) && isset($value['end'])) {
                        $value = array('start' => date('Y-m-d H:i:s', strtotime($value['start'])),
                                       'end' => date('Y-m-d H:i:s', strtotime($value['end'])));
                    }
                }
                if ($value instanceof \DateTime) {
                    $value = array('start' => $value->format('Y-m-d H:i:s'));
                }
                if ($value instanceof \DatePeriod || $value instanceof PodioItem\Values\Date) {
                    $final = array();
                    foreach ($value as $date) {
                        if (isset($final['start'])) {
                            $final['end'] = $date->format('Y-m-d H:i:s');
                            break;
                        }
                        $final['start'] = $date->format('Y-m-d H:i:s');
                    }
                    $value = $final;
                }
                $value = array($value);
                break;
            case 'image' :
                if (is_int($value)) {
                    $value = array('file_id' => $value);
                } elseif (is_string($value)) {
                    $value = array('link' => $value);
                }
            case 'question' :
            case 'category' :
                if ($type === 'question' || $type === 'category') {
                    if ($structure['config']['multiple']) {
                        if (!is_array($value) || isset($value['id'])) {
                            $value = array($this->selectOption($structure['config']['options'], $value));
                        } else {
                            foreach ($value as $i => $v) {
                                $value[$i] = $this->selectOption($structure['config']['options'], $v);
                            }
                        }
                    } else {
                        $value = array($this->selectOption($structure['config']['options'], $value));
                    }
                    return $value;
                }
            case 'money' :
                if ($type == 'money') {
                    if (is_numeric($value)) {
                        $value = array('currency' => 'USD', 'value' => $value);
                    } elseif (is_string($value)) {
                        $parser = new Currency;
                        list($currency, $value) = $parser->parse($value, $structure['config']);
                        $value = array('currency' => $currency, 'value' => $value);
                    }
                }
            case 'number' :
                if ($type === 'number') {
                    if (!is_numeric($value)) {
                        throw new \Exception('Cannot set a number to a non-numeric value');
                    }
                    $value = (real) $value;
                }
            case 'progress' :
                if ($type === 'progress') {
                    if (!is_int($value) || $value < 0 || $value > 100) {
                        throw new \Exception('progress field must be between 0 and 100');
                    }
                }
            case 'duration' :
                if ($type === 'duration') {
                    if (is_string($value)) {
                        $value = \DateInterval::createFromDateString($value);
                    }
                    if ($value instanceof \DateInterval) {
                        $value = (int) $this->getDuration($value);
                    }
                    if (!is_int($value)) {
                        throw new \Exception('Can only set a duration to a value in seconds, a date string, or a DateInterval object');
                    }
                }
            case 'calculation' :
                if ($type === 'calculation') {
                    throw new \Exception('Cannot set a value for calculation fields');
                }
            case 'text' :
            case 'location' :
                if (is_object($value)) {
                    $value = $value->toArray();
                }
                $value = array(array('value' => $value));
                break;
        }
        return $value;
    }

    function selectOption($options, $value)
    {
        if (is_array($value)) {
            if (!isset($value['id'])) {
                // TODO: convert to a specific exception
                throw new \Exception('array passed in is not a valid array for a question or category option');
            }
            $value = $value['id'];
        } elseif ($value instanceof \Chiara\PodioItem\Values\Option) {
            $value = $value->getValue();
        }
        $found = false;
        foreach ($options as $option) {
            if ($option['id'] == $value || $option['text'] == $value) {
                $value = $option;
                $found = true;
                break;
            }
        }
        if (!$found) {
            // TODO: convert to a specific exception
            throw new \Exception('Option value "' . $value . '" not found');
        }
        return array('value' => $value);
    }

    function getDuration(\DateInterval $di)
    {
        return ($di->y * 365 * 24 * 60 * 60) + 
               ($di->m * 30 * 24 * 60 * 60) + 
               ($di->d * 24 * 60 * 60) + 
               ($di->h * 60 * 60) + 
               ($di->i * 60) + 
               $di->s;
    }

    function getType($field)
    {
        if (isset($this->structure[$field])) {
            return $this->structure[$field]['type'];
        }
        throw new \Exception('Unknown field: "' . $field . '" requested for app ' . static::APPNAME);
    }

    function getConfig($field)
    {
        if (isset($this->structure[$field])) {
            return $this->structure[$field]['config'];
        }
        throw new \Exception('Unknown field: "' . $field . '" configuration requested for app ' . static::APPNAME);
    }

    function generateStructureClass($spaceid, $appid, $classname, $namespace = null, $filename = null)
    {
        $ret = "<?php\n";
        if ($namespace) {
            $ret .= "namespace $namespace;\n";
        }
        $ret .= "class $classname extends \\" . get_class($this) . "\n";
        $ret .= "{\n";
        $ret .= '    const APPNAME = "' . $spaceid . '/' . $appid . "\";\n";
        $ret .= '    protected $structure = ';
        $structure = explode("\n", $this->dumpStructure());
        $ret .= $structure[0] . "\n";
        array_shift($structure);
        $structure = array_map(function($a) {return "    $a";}, $structure);
        $ret .= implode("\n", $structure) . ";\n";
        $ret .= "}\n";
        if ($filename) {
            file_put_contents($filename, $ret);
        }
        return $ret;
    }

    function dump()
    {
        var_export($this->structure);
    }
}