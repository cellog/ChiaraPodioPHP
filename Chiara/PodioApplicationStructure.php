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

    function addField($name, $type, $config = null)
    {
        $this->structure[$name] = array('type' => $type, 'config' => $config);
    }

    function addTextField($name)
    {
        $this->structure[$name] = array('type' => 'text', 'config' => null);
    }

    function addNumberField($name)
    {
        $this->structure[$name] = array('type' => 'number', 'config' => null);
    }

    function addImageField($name)
    {
        $this->structure[$name] = array('type' => 'image', 'config' => null);
    }

    function addDateField($name)
    {
        $this->structure[$name] = array('type' => 'date', 'config' => null);
    }

    function addAppField($name, array $referenceable_types)
    {
        $this->structure[$name] = array('type' => 'app', 'config' => $referenceable_types);
    }

    function addMoneyField($name, array $allowed_currencies)
    {
        $this->structure[$name] = array('type' => 'money', 'config' => $allowed_currencies);
        
    }

    function addProgressField($name)
    {
        $this->structure[$name] = array('type' => 'progress', 'config' => null);
    }

    function addLocationField($name)
    {
        $this->structure[$name] = array('type' => 'location', 'config' => null);
    }

    function addDurationField($name)
    {
        $this->structure[$name] = array('type' => 'duration', 'config' => null);
    }

    function addContactField($name, $type)
    {
        if (!in_array(array('space_users', 'all_users', 'space_contacts', 'space_users_and_contacts'))) {
            // TODO: convert to custom Chiara exception
            throw new \Exception('Invalid type for contact field "' . $name . '" in app ' . static::APPNAME);
        }
        $this->structure[$name] = array('type' => 'contact', 'config' => $type);
    }

    function addCalculationField($name)
    {
        $this->structure[$name] = array('type' => 'calculation', 'config' => null);
    }

    function addEmbedField($name)
    {
        $this->structure[$name] = array('type' => 'embed', 'config' => null);
    }

    function addQuestionField($name, array $options, $multiple)
    {
        $this->structure[$name] = array('type' => 'question', 'config' => array('options' => $options, 'multiple' => $multiple));
    }

    function addCategoryField($name, array $options, $multiple)
    {
        $this->structure[$name] = array('type' => 'question', 'config' => array('options' => $options, 'multiple' => $multiple));
    }

    /**
     * The "file" field type only exists in legacy Podio apps
     */
    function addFileField($name)
    {
        $this->structure[$name] = array('type' => 'file', 'config' => null);
    }

    /**
     * The "video" field type only exists in legacy Podio apps
     */
    function addVideoField($name)
    {
        $this->structure[$name] = array('type' => 'video', 'config' => null);
    }

    /**
     * The "state" field type only exists in legacy Podio apps
     */
    function addStateField($name, array $allowed_values)
    {
        $this->structure[$name] = array('type' => 'state', 'config' => $allowed_values);
    }

    /**
     * The "media" field type only exists in legacy Podio apps
     */
    function addMediaField($name)
    {
        $this->structure[$name] = array('type' => 'media', 'config' => null);
    }

    /**
     * translate a Podio app downloaded from the API into a structure object
     */
    function structureFromApp(PodioApp $app)
    {
        foreach ($app->fields as $field) {
            switch ($field->type) {
                case 'state' :
                    $this->addStateField($field->name, $field->allowed_values);
                    break;
                case 'app' :
                    $this->addAppField($field->name, $field->referenceable_types);
                    break;
                case 'money' :
                    $this->addMoneyField($field->name, $field->allowed_currencies);
                    break;
                case 'contact' :
                    $this->addMoneyField($field->name, $field->allowed_currencies);
                    break;
                case 'question' :
                    $this->addQuestionField($field->name, $field->options, $field->multiple);
                    break;
                case 'category' :
                    $this->addCategoryField($field->name, $field->options, $field->multiple);
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
                    $this->addField($field->name, $field->type);
                    break;
            }
        }
        self::$structures[$app->workspace_url . '/' . $app->external_id] = array($this->structure, get_class($this));
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
}