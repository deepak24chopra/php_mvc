<?php

  function dnd($data) {
    echo "<pre>";
    var_dump($data);
    echo '</pre>';
    die();
  }

  function sanitize($dirty_value) {
  	return htmlentities($dirty_value, ENT_QUOTES, 'UTF-8');
  }
