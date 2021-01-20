<?php

namespace SnapiFile;

class Store {
  private $file;
  private $resource = false;
  private $contents = array();

  function __construct($file) {
    $this->file = $file;
  }

  function __destruct() {
    if ($this->resource !== false) {
      fclose($this->resource);
    }
  }

  public function load() {
    if (!file_exists($this->file)) {
      $this->save();
    }

    $this->resource = fopen($this->file, 'a+');
    flock($this->resource, LOCK_EX);

    $data = stream_get_contents($this->resource);
    if (empty($data)) {
      $data = '{}';
    }
    $this->contents = json_decode($data, true);
  }

  public function save() {
    $data = json_encode($this->contents);
    ftruncate($this->resource, 0);
    fwrite($this->resource, $data);
    fflush($this->resource);
  }

  public function create($value, $key = null) {
    if ($key !== null) {
      if (!$this->exists($key)) {
        $this->contents[$key] = $value;
      } else {
        return false;
      }
    } else {
      $this->contents[] = $value;
    }

    return true;
  }

  public function retrieve($key) {
    if ($this->exists($key)) {
      return $this->contents[$key];
    } else {
      return false;
    }
  }

  public function retrieve_all() {
    return $this->contents;
  }

  public function update($key, $value) {
    if ($this->exists($key)) {
      $this->contents[$key] = $value;
    } else {
      return false;
    }

    return true;
  }

  public function delete($key) {
    if ($this->exists($key)) {
      unset($this->contents[$key]);
      return true;
    } else {
      return false;
    }
  }

  public function clear() {
    $this->contents = array();
  }

  private function exists($key) {
    if (array_key_exists($key, $this->contents)) {
      return true;
    } else {
      return false;
    }
  }
}
