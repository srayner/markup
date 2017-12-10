<?php

function objToHtml($obj, $indent, $supress)
{
    $html = $supress ? '' : str_repeat(' ', $indent);
    $html .= '{<br>';
    foreach(get_object_vars($obj) as $key => $value) {
        $html .= keyToHtml($key, $indent + 4);
        $html .= valueToHtml($value, $indent, true);
        $html .= ',<br>';
    }
    $html = substr($html, 0, -5);
    $html .= '<br>' .  str_repeat(' ', $indent) . '}';
    return $html;
}

function keyToHtml($key, $indent)
{
    return str_repeat(' ', $indent) . '<span class="json-property">"' . $key . '"</span>: ';
}

function valueToHtml($value, $indent, $supress)
{
    if (is_object($value)) {
        return objToHtml($value, $indent + 4, $supress);
    }
    if (is_string($value)) {
        $html = $supress ? '' : str_repeat(' ', $indent + 4);
        $html .= '<span class="json-string">"' . $value .'"</span>';
        return $html;
    }
    if (is_numeric($value)) {
        $html = $supress ? '' : str_repeat(' ', $indent + 4);
        $html .= '<span class="json-numeric">' . $value .'</span>';
        return $html;
    }
    if (is_array($value)) {
        $html = '[<br>';
        foreach ($value as $subValue) {
            $html .= valueToHtml($subValue, $indent + 4, false);
            $html .= ',<br>';
        }
        $html = substr($html, 0, -5);
        $html .= '<br>' . str_repeat(' ', $indent + 4) . ']';
        return $html;
    }
    return ''; // should never happen
}
    
$json = file_get_contents('example.json');
$obj = json_decode($json);
$html = '<pre>';
$html .= objToHtml($obj, 0, false);
$html .= '</pre>';

include 'template.phtml';

echo $debug;

