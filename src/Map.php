<?php namespace Dtkahl\ArrayTools;

class Map
{
  /**
   * @var array
   */
  private $_properties = [];

  public function __construct(array $properties = [])
  {
    $this->_properties = $properties;
  }

  /**
   * @param $key
   * @return bool
   */
  public function has($key)
  {
    return array_key_exists((string) $key, $this->_properties);
  }

  /**
   * @param $key
   * @param mixed $default
   * @return null
   */
  public function get($key, $default = null)
  {
    return $this->has((string) $key) ? $this->_properties[(string) $key] : $default;
  }

  /**
   * @param $key
   * @param mixed $value
   * @return $this
   */
  public function set($key, $value)
  {
    $this->_properties[(string) $key] = $value;
    return $this;
  }

  /**
   * @param $key
   * @return $this
   */
  public function remove($key)
  {
    if ($this->has((string) $key)) {
      unset($this->_properties[(string) $key]);
    }
    return $this;
  }

  /**
   * @param array $keys
   * @return Map
   */
  public function except(array $keys)
  {
    return new self(array_diff_key($this->toArray(), array_flip($keys)));
  }

  /**
   * @param array $keys
   * @return Map
   */
  public function only(array $keys)
  {
    return new self(array_intersect_key($this->toArray(), array_flip($keys)));
  }

  /**
   * @return array
   */
  public function toArray()
  {
    return $this->_properties;
  }

  /**
   * @return array
   */
  public function toSerializedArray()
  {
    $array = [];
    foreach ($this->toArray() as $key=>$item) {
      $array[$key] = (is_object($item) && method_exists($item, 'toSerializedArray')) ?
          $item->toSerializedArray() : json_decode(json_encode($item), true);  // Do anyone know a better way? :)
    }
    return $array;
  }

  /**
   * @return string
   */
  public function toJson()
  {
    return json_encode($this->toSerializedArray());
  }

  /**
   * @return array
   */
  public function getKeys()
  {
    return array_keys($this->_properties);
  }

  /**
   * @param $data
   * @return $this
   */
  public function merge($data)
  {
    if ($data instanceof self) {
      $this->_properties = array_merge($this->_properties, $data->toArray());
    } else {
      $this->_properties = array_merge($this->_properties, $data);
    }
    return $this;
  }

  /**
   * @return Map
   */
  public function copy()
  {
    return clone $this;
  }

}
