<?php

require_once  (__DIR__.'/../../../vendor/autoload.php');


class RoboFile extends \Robo\Tasks
{

    private function buildDocs($path, $class)
    {
        $this->taskGenDoc($path)
            ->docClass($class)
            ->processClassSignature(false)
            ->processPropertySignature(false)
            ->processMethodSignature(function(\ReflectionMethod $r, $text) {
                return "#### {$r->name}()";
            })->processProperty(function(\ReflectionProperty $r, $text) {
                if($r->isProtected() || $r->isPrivate()){
                    return false;
                }
                return '#### '.  ($r->isPublic() ? '*public* ' : '' ) .  $r->name . " \n $text";
            })->run();
    }

    /**
     * run cmd robo build
     */
    function build()
    {
        // widgets
        $this->buildDocs('docs\grid-view.md','\kak\widgets\grid\GridView');
        // columns
        $this->buildDocs('docs\columns\data-column.md','\kak\widgets\grid\columns\DataColumn');
        $this->buildDocs('docs\columns\label-column.md','\kak\widgets\grid\columns\LabelColumn');
        // behaviors
        $this->buildDocs('docs\behaviors\toolbar-behavior.md','\kak\widgets\grid\behaviors\ToolBarBehavior');
        $this->buildDocs('docs\behaviors\export-table-behavior.md','\kak\widgets\grid\behaviors\ExportTableBehavior');
        $this->buildDocs('docs\behaviors\menu-column-behavior.md','\kak\widgets\grid\behaviors\MenuColumnsBehavior');
        $this->buildDocs('docs\behaviors\page-size-behavior.md','\kak\widgets\grid\behaviors\PageSizeBehavior');
        $this->buildDocs('docs\behaviors\resizable-columns-behavior.md','\kak\widgets\grid\behaviors\ResizableColumnsBehavior');

    }

}