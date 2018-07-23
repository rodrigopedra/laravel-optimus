<?php

namespace RodrigoPedra\LaravelOptimus\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use RuntimeException;
use Symfony\Component\Process\Process;

class GenerateOptimusKeys extends Command
{
    protected $signature = 'optimus:seed {prime?}';

    protected $description = 'Generate optimus secret numbers';

    public function handle()
    {
        $prime = $this->argument( 'prime' );

        $command = join( DIRECTORY_SEPARATOR, [ base_path(), 'vendor', 'bin', 'optimus' ] );
        $command = join( ' ', array_filter( [ $command, 'spark', $prime ] ) );

        $process = new Process( $command );

        try {
            $output  = $process->mustRun()->getOutput();
            $numbers = $this->extractFromOutput( $output );
        } catch ( Exception $exception ) {
            $this->error( 'Error on running command' );

            return;
        }

        foreach ($numbers as $key => $value) {
            $this->info( $key . '=' . $value );
        }
        $this->info( '' );
    }

    private function extractFromOutput( $output )
    {
        $results = [];

        preg_replace_callback(
            '/(Prime|Inverse|Random): (\d+)/m',
            function ( $matches ) use ( &$results ) {
                $key   = 'OPTIMUS_' . strtoupper( $matches[ 1 ] );
                $value = $matches[ 2 ];

                $results[ $key ] = $value;
            },
            $output
        );

        if (is_null( $results[ 'OPTIMUS_PRIME' ] ?? null )
            || is_null( $results[ 'OPTIMUS_INVERSE' ] ?? null )
            || is_null( $results[ 'OPTIMUS_RANDOM' ] ?? null )) {
            throw new RuntimeException( 'Could not generate keys' );
        }

        return $results;
    }
}
