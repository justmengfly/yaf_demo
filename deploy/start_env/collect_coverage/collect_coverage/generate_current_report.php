<?php

$coverage = apcu_fetch("coverage");
if ($coverage == false)
{
    $coverage = array();
}

$timestamp = time();
$xml = new XMLWriter();
$xml->openMemory();
$xml->startDocument('1.0','UTF-8');
$xml->setIndent(4);
$xml->startElement('coverage');
$xml->writeAttribute('generated', $timestamp);
$xml->startElement('project');
$xml->writeAttribute('timestamp', $timestamp);

$sum_ncloc = 0;
$sum_methods = 0;
$sum_coveredmethods = 0;
$sum_statements = 0;
$sum_coveredstatements = 0;
$sum_elements = 0;
$sum_coveredelements = 0;

function generate_line_element($line_coverage, $line_number, $xml)
{
    # $xml->startElement('line');
    # $xml->writeAttribute('num', $line_number);
    # $xml->writeAttribute('type', 'stmt');
    # $xml->writeAttribute('value', $line_coverage);
    # $xml->endElement();

    $GLOBALS['loc']++;
    if ($line_coverage > -2) {
        $GLOBALS['ncloc']++;
    }
    $GLOBALS['statements']++;
    if ($line_coverage >= 1) {
        $GLOBALS['coveredstatements']++;
    }
}

function gerenate_file_element($file_coverage, $filename, $xml)
{
    $xml->startElement('file');
    $xml->writeAttribute('name', $filename);

    # $content = file_get_contents($filename);
    # try {
    #     $parser = new PHPFuncParser($content);
    #     $function_list = $parser -> process();
    #     $GLOBALS['function_list'] = $function_list;
    # } catch (RuntimeException $e) {
    #     var_dump($e->getMessage());
    #     exit(1);
    # }

    $GLOBALS['loc'] = 0;
    $GLOBALS['ncloc'] = 0;
    $GLOBALS['methods'] = 0;
    $GLOBALS['coveredmethods'] = 0;
    $GLOBALS['statements'] = 0;
    $GLOBALS['coveredstatements'] = 0;
    $GLOBALS['elements'] = 0;
    $GLOBALS['coveredelements'] = 0;

    array_walk($file_coverage, 'generate_line_element', $xml);

    $GLOBALS['elements'] = $GLOBALS['methods'] + $GLOBALS['statements'];
    $GLOBALS['coveredelements'] = $GLOBALS['coveredmethods'] + $GLOBALS['coveredstatements'];

    $xml->startElement('metrics');
    $xml->writeAttribute('loc', $GLOBALS['loc']);
    $xml->writeAttribute('ncloc', $GLOBALS['ncloc']);
    $xml->writeAttribute('methods', $GLOBALS['methods']);
    $xml->writeAttribute('coveredmethods', $GLOBALS['coveredmethods']);
    $xml->writeAttribute('statements', $GLOBALS['statements']);
    $xml->writeAttribute('coveredstatements', $GLOBALS['coveredstatements']);
    $xml->writeAttribute('elements', $GLOBALS['elements']);
    $xml->writeAttribute('coveredelements', $GLOBALS['coveredelements']);

    $GLOBALS['sum_loc'] += $GLOBALS['loc'];
    $GLOBALS['sum_ncloc'] += $GLOBALS['ncloc'];
    $GLOBALS['sum_methods'] += $GLOBALS['methods'];
    $GLOBALS['sum_coveredmethods'] += $GLOBALS['coveredmethods'];
    $GLOBALS['sum_statements'] += $GLOBALS['statements'];
    $GLOBALS['sum_coveredstatements'] += $GLOBALS['coveredstatements'];
    $GLOBALS['sum_elements'] += $GLOBALS['elements'];
    $GLOBALS['sum_coveredelements'] += $GLOBALS['coveredelements'];

    $xml->endElement();
    $xml->endElement();
}

array_walk($coverage, 'gerenate_file_element', $xml);

$xml->startElement('metrics');
$xml->writeAttribute('loc', $GLOBALS['sum_loc']);
$xml->writeAttribute('ncloc', $GLOBALS['sum_ncloc']);
$xml->writeAttribute('methods', $GLOBALS['sum_methods']);
$xml->writeAttribute('coveredmethods', $GLOBALS['sum_coveredmethods']);
$xml->writeAttribute('statements', $GLOBALS['sum_statements']);
$xml->writeAttribute('coveredstatements', $GLOBALS['sum_coveredstatements']);
$xml->writeAttribute('elements', $GLOBALS['sum_elements']);
$xml->writeAttribute('coveredelements', $GLOBALS['sum_coveredelements']);
$xml->endElement();

$xml->endElement();
$xml->endElement();
$xml->endDocument();
#$xml->flush();
header("Content-type: text/xml");
print($xml->outputMemory());

