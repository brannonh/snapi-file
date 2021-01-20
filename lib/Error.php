<?php

namespace SnapiFile;

class Error {
  function __construct($message = 'There was an unknown error.', $error_code = 'ELpfVvQPUih8autIx3il5', $http_code = 500, $die = true) {
    http_response_code($http_code);
    $error = array(
      'error' => array(
        'code' => $error_code,
        'message' => $message,
      )
    );

    echo json_encode($error);

    if ($die) {
      die();
    }
  }
}
