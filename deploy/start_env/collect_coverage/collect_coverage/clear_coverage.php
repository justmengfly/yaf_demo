<?php

if ($_GET['clear'] == "true")
{
    apcu_delete("coverage");
    print("done!");
    exit();
} else {
    print("need param 'clear' be 'true'");
}
