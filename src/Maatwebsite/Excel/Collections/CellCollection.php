<?php namespace Maatwebsite\Excel\Collections;

/**
 *
 * LaravelExcel CellCollection
 *
 * @category   Laravel Excel
 * @version    1.0.0
 * @package    maatwebsite/excel
 * @copyright  Copyright (c) 2013 - 2014 Maatwebsite (http://www.maatwebsite.nl)
 * @author     Maatwebsite <info@maatwebsite.nl>
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 */
class CellCollection {

    /**
     * Create a new collection.
     *
     * @param  array  $items
     * @return void
     */
    public function __construct(array $items = array())
    {
        $this->setItems($items);
    }

    /**
     * Create a new collection instance if the value isn't one already.
     *
     * @param  mixed  $items
     * @return \Illuminate\Support\Collection
     */
    public static function make($items)
    {
        if (is_null($items)) return new static;
        return new static(is_array($items) ? $items : array($items));
    }

    /**
     * Set the items
     * @param [type] $items [description]
     */
    public function setItems($items)
    {
        foreach($items as $name => $value)
        {
            $this->setItem($name, $value);
        }
    }

    /**
     * Set the item
     * @param [type] $name  [description]
     * @param [type] $value [description]
     */
    public function setItem($name, $value)
    {
        if($name)
            $this->{$name} = $value ? $value : null;
    }

}