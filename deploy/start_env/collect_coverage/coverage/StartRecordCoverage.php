<?php

xdebug_start_code_coverage(1|2);

$GLOBALS["stored_coverage"] = array();

function my_print($msg)
{
    #print($msg);
}

function my_print_r($var)
{
    #print_r($var);
}

function merge_line_coverage($new_line_coverage, $key)
{
    $old_line_coverage = &$GLOBALS["stored_file_coverage"];
    if (array_key_exists($key, $old_line_coverage))
    {
        if ($old_line_coverage[$key] < $new_line_coverage)
        {
            $old_line_coverage[$key] = $new_line_coverage;
        }
    } else {
        $old_line_coverage[$key] = $new_line_coverage;
    }
}

function merge_file_coverage($new_file_coverage, $key)
{
    $stored_coverage = &$GLOBALS["stored_coverage"];
    if (array_key_exists($key, $stored_coverage))
    {
        $GLOBALS["stored_file_coverage"] = &$stored_coverage[$key];
        array_walk($new_file_coverage, 'merge_line_coverage');
    } else {
        $stored_coverage[$key] = $new_file_coverage;
    }
}

function shutdown ()
{
    $previews_coverage = array();
    apcu_add("coverage", $previews_coverage);
    $previews_coverage = apcu_fetch("coverage");

    # $previews_coverage = apcu_fetch("coverage");
    # if ($previews_coverage == false)
    # {
    #     my_print("\$previews_coverage = false\n");
    #     $previews_coverage = array();
    # }

    my_print("\n\npreviews_coverage:\n");
    my_print_r($previews_coverage);
    my_print("\n");

    $GLOBALS["stored_coverage"] = $previews_coverage;

    $coverage = xdebug_get_code_coverage();

    my_print("\n\ncoverage:\n");
    my_print_r($coverage);
    my_print("\n");

    array_walk($coverage, 'merge_file_coverage');

    my_print("\n\nmerged_coverage:\n");
    my_print_r($GLOBALS["stored_coverage"]);
    my_print("\n");

    # apcu_delete("coverage");
    apcu_store("coverage", $GLOBALS["stored_coverage"]);
    xdebug_stop_code_coverage();
}

register_shutdown_function('shutdown');
