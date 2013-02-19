<?php

namespace Karadaras;

/**
 * @author Sergey Tihonov
 */
class Config implements \Iterator
{
    /**
     * @var array
     */
    protected $_data = array();

    /**
     * Подключаем файл в формате json, извлекаем данные и сохраняем в массив.
     * 
     * @param string $configFile
     * @return Config
     */
    public function process($configFile)
    {
        if (!is_readable($configFile)) {
            throw new \InvalidArgumentException(sprintf(
                'Файл конфигурации (%s) не существует или не доступен для чтения.',
                $configFile
            ));
        }

        $sourceFile = file_get_contents($configFile);

        if (!empty($sourceFile)) {
            $data = json_decode($sourceFile, true);
            $this->setData($data);
        }

        return $this;
    }

    /**
     * Устанавливаем данные, если значение параметра является массивом
     * создаем новый экземпляр конфига.
     * 
     * @param array $data
     * @return Config
     */
    public function setData(array $data)
    {
        foreach($data as $key => $value) {
            if (is_array($value)) {
                $configClass = new self();
                $this->_data[$key] = $configClass->setData($value);
            } else {
                $this->_data[$key] = $value;
            }
        }

        return $this;
    }

    /**
     * Магический метод для получения параметров конфигурации.
     * 
     * <code>
     *  $config = ...
     *  $config->variable_name
     * </code>
     * 
     * @param string $key
     * @return mixed
     */
    public function __get($key)
    {
        if (isset($this->_data[$key])) {
            return $this->_data[$key];
        }

        return null;
    }

    /**
     * Магический метод по установки опции конфигурации.
     * 
     * <code>
     *  $config = ...
     *  $config->variable_name = 123;
     * </code>
     * 
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function __set($key, $value)
    {
        $this->_data[$key] = $value;
    }

    /**
     * Получить опции конфига в виде массива.
     * 
     * @return array
     */
    public function toArray()
    {
        $array = array();
        $data  = $this->_data;

        foreach ($data as $key => $value) {
            if ($value instanceof self) {
                $array[$key] = $value->toArray();
            } else {
                $array[$key] = $value;
            }
        }

        return $array;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return trim(implode(';', $this->toArray()), ';');
    }

    /**
     * Проверить, есть ли запрашиваемый ключ в массиве данных.
     * 
     * @param string $key
     * @return bool
     */
    public function __isset($key)
    {
        return isset($this->_data[$key]);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     */
    public function current()
    {
        return current($this->_data);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     */
    public function next()
    {
        next($this->_data);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     */
    public function key()
    {
        return key($this->_data);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     *       Returns true on success or false on failure.
     */
    public function valid()
    {
        return ($this->key() !== null);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     */
    public function rewind()
    {
        reset($this->_data);
    }
}