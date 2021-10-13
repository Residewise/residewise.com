<?php

namespace App\Tests\Setup;

use Doctrine\Bundle\DoctrineBundle\Command\CreateDatabaseDoctrineCommand;
use Doctrine\Bundle\DoctrineBundle\Command\DropDatabaseDoctrineCommand;
use Doctrine\Bundle\DoctrineBundle\Command\Proxy\UpdateSchemaDoctrineCommand;
use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Doctrine\Bundle\FixturesBundle\Command\LoadDataFixturesDoctrineCommand;
use Exception;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\HttpKernel\KernelInterface;

class Database
{
    private static bool $isDatabaseReady = false;

    private const DATABASE_COMMANDS = [
        ['command' => DropDatabaseDoctrineCommand::class, '--force' => true],
        ['command' => CreateDatabaseDoctrineCommand::class],
        ['command' => UpdateSchemaDoctrineCommand::class, '--force' => true],
        ['command' => LoadDataFixturesDoctrineCommand::class],
    ];

    /**
     * @throws Exception
     */
    public static function prepareDatabase(KernelInterface $kernel) : void
    {
        if(!self::$isDatabaseReady){
            self::loadDatabase();
            self::$isDatabaseReady = true;
        }
    }

    /**
     * @throws Exception
     * @return never
     */
    private static function loadDatabase() : void
    {
        $output = new ConsoleOutput();

        foreach (self::DATABASE_COMMANDS as $input){
            $command = self::getApplication()->find($input['command']);

            $arrayInput = new ArrayInput($input);
            $arrayInput->setInteractive(false);

            $doctrine = $kernel->getContainer()->get('doctrine');
            $connection = $doctrine->getConnection();
            if($connection->isConnected())
            {
                $connection->close();
            }

            try
            {
                $command->run($arrayInput, $output);
            }catch (Exception $exception)
            {
                throw new Exception('Test command could not be run: ' . $exception->getMessage());
            }
        }
    }

    public static function getApplication() : Application
    {
         return new Application();
    }

}
