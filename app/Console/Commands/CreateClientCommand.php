<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Ramsey\Uuid\Uuid;
use App\Client;

class CreateClientCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:client';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "creates a new client";

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $client = new Client;
        $client->client_id = (string)Uuid::uuid4();
        $client->secret    = (string)Uuid::uuid4();
        $client->save();

        echo "client_id: {$client->client_id}\nsecret: {$client->secret}\n\n";
        echo '$image = new \PixelStorage\Client("http://localhost:9999",'
            . var_export($client->client_id, true) . ', '
            . var_export($client->secret, true). ');';
        echo "\n\n";
    }

}
