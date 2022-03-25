<?php


namespace libs\cdn\abstracts;

use Exception;
use libs\cdn\interfaces\Interfaces;

abstract class Abstracts implements Interfaces
{
    public $host;

    protected $lastError = null;

    protected $client;

    public function init()
    {
        if (stripos($this->host, 'http://') !== 0 && stripos($this->host, 'https://') !== 0  && stripos($this->host, '//') !== 0)
            throw new Exception("host must begin with http://, https:// or //");

        if( $this->host[strlen($this->host) - 1] !== '/' )
            $this->host .= '/';
    }

    public function getLastError()
    {
        return is_string( $this->lastError ) ? $this->lastError : print_r($this->lastError, true);
    }

    public function getCdnUrl($destFile)
    {
        if( strpos($destFile, '/') === 0 ){
            $destFile = substr($destFile, 1);
        }
        return $this->host . $destFile;
    }
}