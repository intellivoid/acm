<?php

    use acm\acm;
    use acm\Objects\Schema;

    $SourceDirectory = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR;
    include_once ($SourceDirectory . 'acm' . DIRECTORY_SEPARATOR . 'acm.php');

    $acm = new acm(__DIR__, 'ACM Example');

    $DatabaseConfigSchema = new Schema();
    $DatabaseConfigSchema->setDefinition('host', 'localhost');
    $DatabaseConfigSchema->setDefinition('port', '3306');
    $DatabaseConfigSchema->setDefinition('username', 'root');
    $DatabaseConfigSchema->setDefinition('password', 'd');
    $acm->defineSchema('Database', $DatabaseConfigSchema);

    $acm->processCommandLine();
    var_dump($acm->getConfiguration('Database'));