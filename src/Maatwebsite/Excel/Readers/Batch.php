<?php namespace Maatwebsite\Excel\Readers;

use \Closure;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Exceptions\LaravelExcelException;

/**
 *
 * LaravelExcel Batch Importer
 *
 * @category   Laravel Excel
 * @version    1.0.0
 * @package    maatwebsite/excel
 * @copyright  Copyright (c) 2013 - 2014 Maatwebsite (http://www.maatwebsite.nl)
 * @author     Maatwebsite <info@maatwebsite.nl>
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 */
class Batch {

    /**
     * Excel object
     * @var [type]
     */
    protected $excel;

    /**
     * Batch files
     * @var [type]
     */
    public $files = array();

    /**
     * Set allowed file extensions
     * @var array
     */
    protected $allowedFileExtensions = array(
        'xls',
        'xlsx',
        'csv'
    );

    /**
     * Start the batch
     * @return [type] [description]
     */
    public function start(Excel $excel, $files, Closure $callback)
    {
        // Set excel object
        $this->excel = $excel;

         // Set files
        $this->_setFiles($files);

        // Do the callback
        if($callback instanceof Closure)
        {
            foreach($this->getFiles() as $file)
            {
                // Load the file
                $excel = $this->excel->load($file);

                // Do a callback with the loaded file
                call_user_func($callback, $excel, $file);
            }
        }

        // Return our excel object
        return $this->excel;
    }

    /**
     * Get the files
     * @return [type] [description]
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * Set the batch files
     * @param [type] $files [description]
     */
    protected function _setFiles($files)
    {
        // If the param is an array, these will be the files for the batch import
        if(is_array($files))
        {
            $this->files = $this->_getFilesByArray($files);
        }

        // Get all the files inside a folder
        elseif(is_string($files))
        {
            $this->files = $this->_getFilesByFolder($files);
        }

        // Check if files were found
        if(empty($this->files))
            throw new LaravelExcelException('[ERROR]: No files were found. Batch terminated.');
    }

    /**
     * Set files by array
     * @param  [type] $array [description]
     * @return [type]        [description]
     */
    protected function _getFilesByArray($array)
    {
        // Make sure we have real paths
        foreach($array as $i => $file)
        {
            $this->files[$i] = realpath($file) ? $file : base_path($file);
        }
    }

    /**
     * Get all files inside a folder
     * @param  [type] $folder [description]
     * @return [type]         [description]
     */
    protected function _getFilesByFolder($folder)
    {
        // Check if it's a real path
        if(!realpath($folder))
            $folder = base_path($folder);

        // Find path names matching our pattern of excel extensions
        $glob = glob($folder.'/*.{'. implode(',', $this->allowedFileExtensions) .'}', GLOB_BRACE);

        // If no matches, return empty array
        if ($glob === false) return array();

        // Return files
        return array_filter($glob, function($file) {
            return filetype($file) == 'file';
        });
    }
}