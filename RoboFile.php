<?php

require_once  (__DIR__.'/../../../vendor/autoload.php');


class RoboFile extends \Robo\Tasks
{
    function build()
    {
        $this->taskGenDoc('docs\grid-view.md')
            ->docClass('\kak\widgets\grid\GridView')
            ->processClassSignature(false) // false can be passed to not include class signature
            ->processClassDocBlock(function(\ReflectionClass $r, $text) {
                return "[GridView]\n" . $text;
            })->processMethodSignature(function(\ReflectionMethod $r, $text) {
                return "#### {$r->name}()";
            })->processMethodDocBlock(function(\ReflectionMethod $r, $text) {
                return strpos($r->name, 'save')===0 ? "[Saves to the database]\n" . $text : $text;
            })->run();

    }

}