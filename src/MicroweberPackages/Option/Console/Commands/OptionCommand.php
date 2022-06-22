<?php

namespace MicroweberPackages\Option\Console\Commands;

use Illuminate\Console\Command;

class OptionCommand extends Command
{
    protected $name = 'microweber:option';
    protected $description = 'Set Microweber option';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'microweber:option {--option_key=} {--option_value=} {--option_group=}';

    public function handle()
    {
        $input = array(
            'option_key' => $this->option('option_key'),
            'option_value' => $this->option('option_value'),
            'option_group' => $this->option('option_group'),
        );

        $this->info('Setting option...');
        $result = mw()->option_manager->save($input);
        $this->info($result);
    }

}
