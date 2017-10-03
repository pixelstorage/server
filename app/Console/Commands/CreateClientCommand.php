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
    protected $description = "Serve the application on the PHP development server";
 
    /** 
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $client = new Client;
        $client->client_id = Uuid::uuid4();
        $client->secret    = Uuid::uuid4();
        $client->save();

        echo "client_id: {$client->client_id}\nsecret: {$client->secret}\n";
    }

}
