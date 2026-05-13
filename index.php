<?php
require_once __DIR__ . "/gts_functions.php";
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>CHIM GTS Bridge</title>
  <style>body{font-family:sans-serif;max-width:900px;margin:2rem auto;line-height:1.4}code,pre{background:#eee;padding:.2rem .4rem}</style>
</head>
<body>
  <h1>CHIM GTS Bridge</h1>
  <p>This plugin registers the Papyrus-backed <code>ExtCmdGTSAction</code> function for CHIM.</p>
  <p>Command request format:</p>
  <pre>action_key|target_name|amount</pre>
  <p>Examples:</p>
  <pre>gts_grab|Player|0.25
gts_grow_self|self|0.50
gts_stomp|Whiterun Guard|0.25</pre>
  <p>Targets may be <code>Player</code>, <code>self</code>, <code>crosshair</code>, or an exact visible/nearby actor display name.</p>
</body>
</html>
