<?php

namespace kak\widgets\grid\services\writer;
use \Box\Spout\Writer\AbstractWriter;
use yii\base\Arrayable;
use yii\helpers\StringHelper;

class WriterXml extends AbstractWriter
{
    /**
     * @var string the XML version
     */
    public $version = '1.0';
    /**
     * @var string the XML encoding.
     */
    public $encoding = 'utf-8';
    /**
     * @var string the name of the elements that represent the array elements with numeric keys.
     */
    public $rootTag = 'row';
    public $itemTag = 'item';

    public $useTraversableAsArray = true;
    public $useObjectTags = true;


    /**
     * @var string Content-Type value for the header
     */
    protected static $headerContentType = 'application/xml';
    /**
     * @inheritdoc
     */

    /**
     * @var \DOMDocument
     */
    private $_dom;

    protected function openWriter()
    {
        $this->_dom = new \DOMDocument($this->version, $this->encoding);


    }

    /**
     * @inheritdoc
     */
    protected function addRowToWriter(array $dataRow, $style)
    {
        $root = new \DOMElement($this->rootTag);
        $this->_dom->appendChild($root);
        $this->buildXml($root, $dataRow);
    }

    /**
     * @inheritdoc
     */
    protected function closeWriter()
    {
        fwrite($this->filePointer, $this->_dom->saveXML());
    }

    //
    /**
     * @param \DOMElement $element
     * @param mixed $data
     */
    protected function buildXml($element, $data)
    {
        if (is_array($data) ||
            ($data instanceof \Traversable && $this->useTraversableAsArray && !$data instanceof Arrayable)
        ) {
            foreach ($data as $name => $value) {
                if (is_int($name) && is_object($value)) {
                    $this->buildXml($element, $value);
                } elseif (is_array($value) || is_object($value)) {
                    $child = new \DOMElement($this->getValidXmlElementName($name));
                    $element->appendChild($child);
                    $this->buildXml($child, $value);
                } else {
                    $child = new \DOMElement($this->getValidXmlElementName($name));
                    $element->appendChild($child);
                    $child->appendChild(new \DOMText($this->formatScalarValue($value)));
                }
            }
        } elseif (is_object($data)) {
            if ($this->useObjectTags) {
                $child = new \DOMElement(StringHelper::basename(get_class($data)));
                $element->appendChild($child);
            } else {
                $child = $element;
            }
            if ($data instanceof Arrayable) {
                $this->buildXml($child, $data->toArray());
            } else {
                $array = [];
                foreach ($data as $name => $value) {
                    $array[$name] = $value;
                }
                $this->buildXml($child, $array);
            }
        } else {
            $element->appendChild(new \DOMText($this->formatScalarValue($data)));
        }
    }

    /**
     * Formats scalar value to use in XML text node.
     *
     * @param int|string|bool|float $value a scalar value.
     * @return string string representation of the value.
     * @since 2.0.11
     */
    protected function formatScalarValue($value)
    {
        if ($value === true) {
            return 'true';
        }
        if ($value === false) {
            return 'false';
        }
        if (is_float($value)) {
            return StringHelper::floatToString($value);
        }
        return (string) $value;
    }

    /**
     * Returns element name ready to be used in DOMElement if
     * name is not empty, is not int and is valid.
     *
     * Falls back to [[itemTag]] otherwise.
     *
     * @param mixed $name
     * @return string
     * @since 2.0.12
     */
    protected function getValidXmlElementName($name)
    {
        if (empty($name) || is_int($name) || !$this->isValidXmlName($name)) {
            return $this->itemTag;
        }

        return $name;
    }

    /**
     * Checks if name is valid to be used in XML.
     *
     * @param mixed $name
     * @return bool
     * @see http://stackoverflow.com/questions/2519845/how-to-check-if-string-is-a-valid-xml-element-name/2519943#2519943
     * @since 2.0.12
     */
    protected function isValidXmlName($name)
    {
        try {
            new \DOMElement($name);
            return true;
        } catch (\DOMException $e) {
            return false;
        }
    }



}