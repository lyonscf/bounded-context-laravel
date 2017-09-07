<?php
/**
 * If you rename/move a command class, point its ID to it's class
 * This is so you don't have to write an explicit upgrader for renaming
 *
 * eg. 
 * return [
    '00000000-0000-0000-0000-000000000000' =>
        \Domain\Namespace\Of\Command::class,
 * ];
 */

return [
   
];
