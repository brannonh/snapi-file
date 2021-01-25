<?php

namespace SnapiFile;

class Api {
  private $file;
  private $storeName;
  private $store;
  private $action;
  private $key;
  private $value;

  public function __construct($storeFile = 'stores.json') {
    $this->file = $storeFile;
    $this->get_route();
    $this->load_store();
    $this->validate_action();
  }

  private function get_route() {
    $this->storeName = htmlentities($_REQUEST['store'], ENT_QUOTES);
    $this->action = htmlentities($_REQUEST['action'], ENT_QUOTES);
    $this->key = array_key_exists('key', $_REQUEST) ? htmlentities($_REQUEST['key'], ENT_QUOTES) : null;
    $this->value = json_decode($_REQUEST['value'], ENT_QUOTES);
  }

  private function load_store() {
    if (file_exists($this->file)) {
      $contents = file_get_contents($this->file);
      $contents = json_decode($contents, true);

      $this->validate_store($contents);
      $this->store = new Store($contents[$this->storeName]);
      $this->store->load();
    }
  }

  private function validate_store($stores) {
    if (!array_key_exists($this->storeName, $stores)) {
      new Error(
        "Store not found.",
        'OUJwyv5dRiPqI6QxGPlGy',
        404
      );
    }
  }

  private function validate_action() {
    if (!in_array($this->action, array('create', 'retrieve', 'update', 'delete',))) {
      new Error(
        "Action invalid.",
        'pjQKclvCTDKPc2zXSXzWJ',
        400
      );
    }
  }

  public function go() {
    $result = false;
    $error = array(
      'message' => 'Data not modified.',
      'httpCode' => 500,
    );

    switch ($this->action) {
      case 'create':
        $result = $this->store->create($this->value, $this->key);
        if ($result === false) {
          $error['message'] = "Data exists.";
          $error['httpCode'] = 409;
        }
        break;
      case 'retrieve':
        if (empty($this->key)) {
          $result = $this->store->retrieve_all();
        } else {
          $result = $this->store->retrieve($this->key);
        }
        if ($result === false) {
          $error['message'] = 'Data not found.';
          $error['httpCode'] = 404;
        }
        break;
      case 'update':
        $result = $this->store->update($this->key, $this->value);
        if ($result === false) {
          $error['message'] = "Data not found.";
          $error['httpCode'] = 404;
        }
        break;
      case 'delete':
        if (empty($this->key)) {
          $result = $this->store->clear();
        } else {
          $result = $this->store->delete($this->key);
        }
        if ($result === false) {
          $error['message'] = "Data not found.";
          $error['httpCode'] = 404;
        }
        break;
    }

    if ($result !== false) {
      $this->store->save();
    } else {
      new Error(
        $error['message'],
        'JmKRsFLFRGM6vvqlQL6AO',
        $error['httpCode']
      );
    }
  }
}
