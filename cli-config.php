<?php
/**
 * Referenced from Doctrine official doc
 * @see https://www.doctrine-project.org/projects/doctrine-orm/en/2.6/reference/configuration.html#setting-up-the-commandline-tool
 */

require_once "bootstrap.php";

return Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($em);