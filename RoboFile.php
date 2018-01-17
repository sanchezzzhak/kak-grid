<?php

require_once  (__DIR__.'/../../../vendor/autoload.php');


class RoboFile extends \Robo\Tasks
{

    /**
     * run cmd robo build
     */
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


        $this->taskGenDoc('docs\columns\data-column.md')
            ->docClass('\kak\widgets\grid\columns\DataColumn')
            ->processClassSignature(false) // false can be passed to not include class signature
            ->processClassDocBlock(function(\ReflectionClass $r, $text) {
                return "[DataColumn]\n" . $text;
            })->processMethodSignature(function(\ReflectionMethod $r, $text) {
                return "#### {$r->name}()";
            })->processMethodDocBlock(function(\ReflectionMethod $r, $text) {
                return strpos($r->name, 'save')===0 ? "[Saves to the database]\n" . $text : $text;
            })->run();


        // behaviors

        $this->taskGenDoc('docs\behaviors\toolbar-behavior.md')
            ->docClass('\kak\widgets\grid\behaviors\ToolBarBehavior')
            ->processClassSignature(false) // false can be passed to not include class signature
            ->processClassDocBlock(function(\ReflectionClass $r, $text) {
                return "[ToolBarBehavior]\n" . $text;
            })->processMethodSignature(function(\ReflectionMethod $r, $text) {
                return "#### {$r->name}()";
            })->processMethodDocBlock(function(\ReflectionMethod $r, $text) {
                return strpos($r->name, 'save')===0 ? "[Saves to the database]\n" . $text : $text;
            })->run();


        $this->taskGenDoc('docs\behaviors\export-table-behavior.md')
            ->docClass('\kak\widgets\grid\behaviors\ExportTableBehavior')
            ->processClassSignature(false) // false can be passed to not include class signature
            ->processClassDocBlock(function(\ReflectionClass $r, $text) {
                return "[ExportTableBehavior]\n" . $text;
            })->processMethodSignature(function(\ReflectionMethod $r, $text) {
                return "#### {$r->name}()";
            })->processMethodDocBlock(function(\ReflectionMethod $r, $text) {
                return strpos($r->name, 'save')===0 ? "[Saves to the database]\n" . $text : $text;
            })->run();


        $this->taskGenDoc('docs\behaviors\menu-column-behavior.md')
            ->docClass('\kak\widgets\grid\behaviors\MenuColumnsBehavior')
            ->processClassSignature(false) // false can be passed to not include class signature
            ->processClassDocBlock(function(\ReflectionClass $r, $text) {
                return "[MenuColumnsBehavior]\n" . $text;
            })->processMethodSignature(function(\ReflectionMethod $r, $text) {
                return "#### {$r->name}()";
            })->processMethodDocBlock(function(\ReflectionMethod $r, $text) {
                return strpos($r->name, 'save')===0 ? "[Saves to the database]\n" . $text : $text;
            })->run();


        $this->taskGenDoc('docs\behaviors\page-size-behavior.md')
            ->docClass('\kak\widgets\grid\behaviors\PageSizeBehavior')
            ->processClassSignature(false) // false can be passed to not include class signature
            ->processClassDocBlock(function(\ReflectionClass $r, $text) {
                return "[PageSizeBehavior]\n" . $text;
            })->processMethodSignature(function(\ReflectionMethod $r, $text) {
                return "#### {$r->name}()";
            })->processMethodDocBlock(function(\ReflectionMethod $r, $text) {
                return strpos($r->name, 'save')===0 ? "[Saves to the database]\n" . $text : $text;
            })->run();


        $this->taskGenDoc('docs\behaviors\resizable-columns-behavior.md')
            ->docClass('\kak\widgets\grid\behaviors\ResizableColumnsBehavior')
            ->processClassSignature(false) // false can be passed to not include class signature
            ->processClassDocBlock(function(\ReflectionClass $r, $text) {
                return "[ResizableColumnsBehavior]\n" . $text;
            })->processMethodSignature(function(\ReflectionMethod $r, $text) {
                return "#### {$r->name}()";
            })->processMethodDocBlock(function(\ReflectionMethod $r, $text) {
                return strpos($r->name, 'save')===0 ? "[Saves to the database]\n" . $text : $text;
            })->run();
    }

}