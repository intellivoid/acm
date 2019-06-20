<?php


    namespace acm;

    use acm\Exceptions\NoConfigurationFoundException;
    use Exception;

    /**
     * Class CommandLine
     * @package acm
     */
    class CommandLine
    {
        /**
         * @var acm
         */
        private $acm;

        /**
         * CommandLine constructor.
         * @param acm $acm
         */
        public function __construct(acm $acm)
        {
            $this->acm = $acm;
        }

        /**
         * Processes the command-line command
         */
        public function processCommandLine()
        {
            if(PHP_SAPI !== 'cli')
            {
                return;
            }

            switch($_SERVER['argv'][1])
            {
                case "build-mc":
                    $this->buildMasterConfiguration();
                    break;

                default:
                    print(" acm Usage Commands\n");
                    print("     build-mc    = Builds the master configuration file\n");
                    print("     status      = Displays how configuration is managed in this setup\n");
                    break;
            }
        }

        /**
         * Builds the master configuration
         * @throws NoConfigurationFoundException
         */
        public function buildMasterConfiguration()
        {
            if($this->acm->isMasterConfigurationLoaded() == false)
            {
                print("Cannot build master configuration because it isn't loaded.\n");
                print("Check '" . acm::getWorkingDirectory() . "' for errors\n");
                exit(1);
            }

            $LocalConfiguration = $this->acm->getBaseDirectory() . DIRECTORY_SEPARATOR . 'configuration.ini';
            print("Checking if '$LocalConfiguration' exists\n");
            $ParsedConfiguration = null;
            if(file_exists($LocalConfiguration) == true)
            {
                print("Parsing '$LocalConfiguration'\n");
                $ParsedConfiguration = parse_ini_file($LocalConfiguration, true);
            }
            else
            {
                print("Skipping local configuration since it doesn't exist\n");
            }

            print("Building configuration from schema\n");
            foreach($this->acm->getMasterConfiguration()['schemas'] as $schemaName => $schemaValue)
            {
                print("Parsing $schemaName\n");
                foreach($this->acm->getMasterConfiguration()['schemas'][$schemaName] as $schemaKey => $defaultValue)
                {
                    print("Creating $schemaKey=>'$defaultValue' from $schemaName\n");
                    $this->acm->getMasterConfiguration()['configurations'][$schemaName][$schemaKey] = $defaultValue;
                }
            }

            // Overwrite default values if possible
            if($ParsedConfiguration !== null)
            {
                print("Building configuration from Local configuration\n");
                foreach($ParsedConfiguration as $configurationName => $configurationValue)
                {
                    print("Parsing $configurationName\n");
                    foreach($ParsedConfiguration[$configurationName] as $configKey => $configValue)
                    {
                        print("Creating $configKey=>'$configValue' from $configurationName\n");
                        $this->acm->getMasterConfiguration()['configurations'][$configurationName][$configKey] = $configValue;
                    }
                }

            }

            print("Updating Master Configuration ...\n");
            $this->acm->updateMasterConfiguration();
            print("Done");
        }
    }