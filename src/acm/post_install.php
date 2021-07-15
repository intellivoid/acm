<?php

    $master_directory = DIRECTORY_SEPARATOR . "etc" . DIRECTORY_SEPARATOR . "acm";
    print("Checking directory '$master_directory'" . PHP_EOL);


    if(file_exists($master_directory) == false)
    {
        print("Directory doesn't exist, creating directory with 0777 permissions" . PHP_EOL);
        mkdir($master_directory);
        chmod($master_directory, "0777");
    }
    else
    {
        print("Check passed, directory already exists" . PHP_EOL);
    }