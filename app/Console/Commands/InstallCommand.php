<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Question\Question;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tweety:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Installation process for Tweety';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->comment($this->tweety());
        $this->welcome();

        $this->createEnvFile();

        if (strlen(config('app.key')) === 0) {
            $this->call('key:generate');

            $this->line('~ Secret key properly generated.');
        }

        $credentials = $this->requestDatabaseCredentials();

        $this->updateEnvironmentFile($credentials);

        if ($this->confirm('Do you want to migrate the database?', false)) {
            $this->migrateDatabaseWithFreshCredentials($credentials);

            $this->line('~ Database successfully migrated.');
        }

        $this->call('cache:clear');

        $this->goodbye();

        return 0;
    }

    public function tweety()
    {
        return '
        ████████╗██╗    ██╗███████╗███████╗████████╗██╗   ██╗
        ╚══██╔══╝██║    ██║██╔════╝██╔════╝╚══██╔══╝╚██╗ ██╔╝
           ██║   ██║ █╗ ██║█████╗  █████╗     ██║    ╚████╔╝
           ██║   ██║███╗██║██╔══╝  ██╔══╝     ██║     ╚██╔╝
           ██║   ╚███╔███╔╝███████╗███████╗   ██║      ██║
           ╚═╝    ╚══╝╚══╝ ╚══════╝╚══════╝   ╚═╝      ╚═╝
        ';
    }

    private function welcome()
    {
        $this->info('>> Welcome to the Tweety installation process! <<');
    }

    private function createEnvFile()
    {
        if (!file_exists('.env')) {
            copy('.env.example', '.env');

            $this->line('.env file successfully created');
        }
    }

    private function requestDatabaseCredentials()
    {
        return [
            'DB_DATABASE' => $this->ask('Database name'),
            'DB_PORT' => $this->ask('Database Port', 3306),
            'DB_USERNAME' => $this->ask('Database username'),
            'DB_PASSWORD' => $this->askHiddenWithDefault('Database password (leave blank for no password)')
        ];
    }

    /**
     * Prompt the user for optional input but hide the answer from the console.
     *
     * @param string $question
     * @param bool $fallback
     * @return string
     */
    private function askHiddenWithDefault($question, $fallback = true)
    {
        $question = new Question($question, null);
        $question->setHidden(true)->setHiddenFallback($fallback);

        return $this->output->askQuestion($question);
    }

    /**
     * Update the .env file from an array of $key => $value pairs.
     *
     * @param array $updatedValues
     * @return void
     */
    private function updateEnvironmentFile($credentials)
    {
        $envFile = $this->laravel->environmentFilePath();

        foreach ($credentials as $key => $value) {
            file_put_contents($envFile, preg_replace("/{$key}=(.*)/",
                "{$key}={$value}",
                file_get_contents($envFile)));
        }
    }

    /**
     * Migrate the db with the new credentials.
     *
     * @param array $credentials
     * @return void
     */
    protected function migrateDatabaseWithFreshCredentials($credentials)
    {
        foreach ($credentials as $key => $value) {
            $configKey = strtolower(str_replace('DB_', '', $key));

            if ($configKey === 'password' && $value == 'null') {
                config(["database.connections.mysql.{$configKey}" => '']);

                continue;
            }

            config(["database.connections.mysql.{$configKey}" => $value]);
        }

        $this->call('migrate');
    }

    /**
     * Display the completion message.
     */
    protected function goodbye()
    {
        $this->info('>> The installation process is successfully completed. Enjoy Tweety! <<');
    }
}
