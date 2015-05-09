<?php
// Prepend this fragment before the first line of Drupal index.php

if (extension_loaded('uprofiler')) {
  $profiler = 'uprofiler';
}
elseif (extension_loaded('xhprof')) {
  $profiler = 'xhprof';
}
else {
  $profiler = NULL;
}
if ($profiler) {
  $enable = "{$profiler}_sample_enable";
  $disable = "{$profiler}_sample_disable";
  $uri = substr($_SERVER['REQUEST_URI'], 1);
  $uri = preg_replace('/[?\/]/', '-', $uri);
  $enable();
  register_shutdown_function(function () use($disable, $profiler, $uri) {
    $filename = "/tmp/xhprof/{$uri}." . uniqid() . ".{$profiler}";
    file_put_contents($filename, serialize($disable()));
    chmod($filename, 0777);
  });
}
