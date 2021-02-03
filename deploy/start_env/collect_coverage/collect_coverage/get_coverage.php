<?php

$coverage = apcu_fetch("coverage");

echo json_encode($coverage);
