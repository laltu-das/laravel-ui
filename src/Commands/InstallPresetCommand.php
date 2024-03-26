<?php

namespace Laltu\LaravelUi\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Illuminate\Filesystem\Filesystem;
use RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;
use function Laravel\Prompts\multiselect;
use function Laravel\Prompts\select;

class InstallPresetCommand extends Command implements PromptsForMissingInput
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'laravel-ui:preset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the Breeze controllers and resources';

    /**
     * Delete the "node_modules" directory and remove the associated lock files.
     *
     * @return void
     */
    protected static function flushNodeModules(): void
    {
        // Remove node_modules directory and lock files
        (new Filesystem)->deleteDirectory(base_path('node_modules'));

        collect(['yarn.lock', 'package-lock.json'])->each(fn($file) => (new Filesystem)->delete(base_path($file)));
    }

    /**
     * Handle the console command.
     *
     * @return int|null
     */
    public function handle(): ?int
    {
        // Get the specified stack (react, vue) from command argument
        $framework = $this->argument('framework');

        // Call appropriate installation method based on the specified stack
        return $framework === 'vue' ? $this->installInertiaVueStack() : $this->installInertiaReactStack();
    }

    /**
     * Install the Inertia Vue Breeze stack.
     *
     * @return int
     */
    protected function installInertiaVueStack(): int
    {
        // Update NPM packages for Vue stack
        $this->updateNodePackages(fn($packages) => [
            "@vueuse/core" => "^10.6.1",
            "classnames" => "^2.3.2",
            "floating-vue" => "^2.0.0-beta.24",
            "lodash-es" => "^4.17.21",
            "tailwind-merge" => "^2.0.0",
            "nanoid" => "^5.0.4"
        ]);

        // Update additional NPM packages for Vue stack
        $this->updateNodePackages(fn($packages) => [
            "laravel-precognition-vue-inertia" => "^0.5.2"
        ], false);

        // Ensure existence of required directories
        collect(['Components', 'Layouts', 'Pages'])->each(
            fn($directory) => (new Filesystem)->ensureDirectoryExists(resource_path("js/{$directory}"))
        );

        // Copy Vue stub files to the resource directory
        $this->copyDirectories('inertia-vue' . ($this->option('stack') == 'typescript' ? '-ts' : ''), 'vue');

        // Install and build Node dependencies
        $this->installAndBuildNodeDependencies();

        // Display success message
        $this->line('');
        $this->info('Breeze scaffolding installed successfully.');

        return 0;
    }

    /**
     * Update the "package.json" file with the provided callback.
     *
     * @param callable $callback
     * @param bool $dev
     * @return void
     */
    protected static function updateNodePackages(callable $callback, bool $dev = true): void
    {
        // Check if "package.json" file exists
        if (!file_exists(base_path('package.json'))) {
            return;
        }

        // Determine whether to update devDependencies or dependencies
        $configurationKey = $dev ? 'devDependencies' : 'dependencies';

        // Read and decode the existing "package.json" file
        $packages = json_decode(file_get_contents(base_path('package.json')), true);

        // Apply the callback to update the specified dependencies
        $updatedDependencies = $callback(
            array_key_exists($configurationKey, $packages) ? $packages[$configurationKey] : [],
            $configurationKey
        );

        // Merge the updated dependencies with existing ones
        $packages[$configurationKey] = array_merge($packages[$configurationKey] ?? [], $updatedDependencies);

        // Sort dependencies alphabetically
        ksort($packages[$configurationKey]);

        // Write the updated packages back to "package.json" file
        file_put_contents(
            base_path('package.json'),
            json_encode($packages, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . PHP_EOL
        );
    }

    protected function copyDirectories(string $stubPath, string $stack): void
    {
        $directories = [];
        $selectedComponents = $this->option('components');
        $selectedLayout = $this->option('layout');
        $filesystem = new Filesystem();

        foreach ($selectedComponents as $component) {

            /*For Components*/
            $componentPath = resource_path("js/Components/Core/{$component}");

            // Check if component directory exists
            if ($filesystem->exists($componentPath)) {
                $overwrite = select("Component {$component} is already installed. Overwrite?", ['yes', 'no']);

                if (!$overwrite) {
                    $this->info("Component {$component} installation skipped.");
                    continue; // Skip to next component
                }
                // If overwriting, first delete the existing component directory
                $filesystem->deleteDirectory($componentPath);
            }

            // Proceed to install (or reinstall) the component
            $sourcePath = __DIR__ . "/../../stubs/{$stubPath}/resources/js/Components/{$component}";
            $destinationPath = resource_path("js/Components/Core/{$component}");
            $filesystem->copyDirectory($sourcePath, $destinationPath);
            $this->info("Component {$component} has been installed.");

            /*For Composable*/
            $composablePath = resource_path("js/Composable/Core/{$component}");
            // Check if Composable directory exists
            if ($filesystem->exists($composablePath)) {
                $overwrite = select("Composable {$component} is already installed. Overwrite?", ['yes', 'no']);

                if (!$overwrite) {
                    $this->info("Composable {$component} installation skipped.");
                    continue; // Skip to next component
                }
                // If overwriting, first delete the existing Composable directory
                $filesystem->deleteDirectory($composablePath);
            }

            $sourcePath = __DIR__ . "/../../stubs/{$stubPath}/resources/js/Composables/{$component}";
            if ($filesystem->exists($sourcePath)) {
                // Proceed to install (or reinstall) the Composable
                $destinationPath = resource_path("js/Composables/Core/{$component}");
                $filesystem->copyDirectory($sourcePath, $destinationPath);
                $this->info("Composables {$component} has been installed.");
            }
        }

        if ($selectedLayout) {

            $layoutPath = resource_path("js/Layouts/AdminLayout.vue");

            // Check if layout directory exists
            if ($filesystem->exists($layoutPath)) {
                $overwrite = select("Admin Layout is already installed. Overwrite?", ['yes', 'no']);

                if (!$overwrite) {
                    $this->info("Admin Layout installation skipped.");
                    exit(); // Skip to next component
                }
                // If overwriting, first delete the existing component directory
                $filesystem->delete($layoutPath);
            }

            $layoutPath = __DIR__ . "/../../stubs/{$stubPath}/resources/js/Layouts/{$selectedLayout}Layout.vue";
            $destinationPath = resource_path("js/Layouts/AdminLayout.vue");
            $filesystem->copy($layoutPath, $destinationPath);
            $this->info("Admin Layout has been installed.");
        }
        // Handle copying of other directories outside of Components
        foreach ($directories as $directory) {
            $sourcePath = __DIR__ . "/../../stubs/{$stubPath}/resources/js/{$directory}";
            $destinationPath = resource_path("js/{$directory}");
            $filesystem->copyDirectory($sourcePath, $destinationPath);
        }

        // Handle TypeScript stubs if needed
        if ($this->option('stack') == 'typescript') {
            $filesystem->copyDirectory(__DIR__ . "/../../stubs/{$stubPath}/resources/js/types", resource_path('js/types'));
        }
    }

    /**
     * Install and build Node dependencies based on the available lock files.
     *
     * @return void
     */
    protected function installAndBuildNodeDependencies(): void
    {
        $commands = [
            file_exists(base_path('pnpm-lock.yaml')) ? 'pnpm install' : (file_exists(base_path('yarn.lock')) ? 'yarn install' : 'npm install'),
            'npm run build',
        ];
        $this->runCommands($commands);
    }

    /**
     * Run the given shell commands.
     *
     * @param array $commands
     * @return void
     */
    protected function runCommands(array $commands): void
    {
        // Create a new Process to run the specified shell commands
        $process = Process::fromShellCommandline(implode(' && ', $commands), null, null, null, null);

        // Enable TTY if applicable
        if ('\\' !== DIRECTORY_SEPARATOR && file_exists('/dev/tty') && is_readable('/dev/tty')) {
            try {
                $process->setTty(true);
            } catch (RuntimeException $e) {
                $this->output->writeln('  <bg=yellow;fg=black> WARN </> ' . $e->getMessage() . PHP_EOL);
            }
        }

        // Run the process and display the output
        $process->run(fn($type, $line) => $this->output->write('    ' . $line));
    }

    /**
     * Install the Inertia React Breeze stack.
     *
     * @return int
     */
    protected function installInertiaReactStack(): int
    {
        // Update NPM packages for React stack
        $this->updateNodePackages(fn($packages) => [
            '@headlessui/react' => '^1.4.2',
            '@inertiajs/react' => '^1.0.0',
            '@tailwindcss/forms' => '^0.5.3',
            '@vitejs/plugin-react' => '^4.0.3',
            'autoprefixer' => '^10.4.12',
            'postcss' => '^8.4.18',
            'tailwindcss' => '^3.2.1',
            'react' => '^18.2.0',
            'react-dom' => '^18.2.0',
        ]);

        // Ensure existence of required directories
        collect(['Components', 'Layouts'])->each(fn($directory) => (new Filesystem)->ensureDirectoryExists(resource_path("js/{$directory}")));

        // Copy React stub files to the resource directory
        $this->copyDirectories('inertia-react' . ($this->option('stack') == 'typescript' ? '-ts' : ''), 'react');

        // Install and build Node dependencies
        $this->installAndBuildNodeDependencies();

        // Display success message
        $this->line('');
        $this->info('Breeze scaffolding installed successfully.');

        return 0;
    }

    protected function getArguments(): array
    {
        return [
            ['framework', InputArgument::REQUIRED, 'The development stack that should be installed (react,vue)'],
        ];
    }

    protected function getOptions(): array
    {
        return [
            ['stack', null, InputOption::VALUE_OPTIONAL, 'Indicates if TypeScript is preferred for the Inertia stack (Experimental)'],
            ['components', null, InputOption::VALUE_OPTIONAL, 'Select your necessary components'],
            ['layout', null, InputOption::VALUE_OPTIONAL, 'Select your dashboard layout'],
        ];
    }

    /**
     * Replace a given string within a given file.
     *
     * @param string $search
     * @param string $replace
     * @param string $path
     * @return void
     */
    protected function replaceInFile(string $search, string $replace, string $path): void
    {
        file_put_contents($path, str_replace($search, $replace, file_get_contents($path)));
    }

    /**
     * Interact further with the user if they were prompted for missing arguments.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function afterPromptingForMissingArguments(InputInterface $input, OutputInterface $output): void
    {
        $framework = $input->getArgument('framework');

        if (in_array($framework, ['react', 'vue'])) {
            $option = select(
                label: 'Would you like any optional features?',
                options: [
                    'js' => 'Core js',
                    'typescript' => 'TypeScript (experimental)'
                ],
                default: 'js'
            );

            $input->setOption('stack', $option);
        }

        $input->setOption('components', multiselect(
            label: 'The development stack that should be installed?',
            options: [
                'Accordion' => 'Accordion',
                'Alert' => 'Alert',
                'Avatar' => 'Avatar',
                'Badge' => 'Badge',
                'Breadcrumb' => 'Breadcrumb',
                'Button' => 'Button',
                'ButtonGroup' => 'ButtonGroup',
                'Card' => 'Card',
                'Carousel' => 'Carousel',
                'Checkbox' => 'Checkbox',
                'Dropdown' => 'Dropdown',
                'Footer' => 'Footer',
                'Form' => 'Form',
                'ListGroup' => 'ListGroup',
                'Modal' => 'Modal',
                'Navbar' => 'Navbar',
                'Pagination' => 'Pagination',
                'Progress' => 'Progress',
                'Radio' => 'Radio',
                'Range' => 'Range',
                'Rating' => 'Rating',
                'Select' => 'Select',
                'Sidebar' => 'Sidebar',
                'Spinner' => 'Spinner',
                'Table' => 'Table',
                'Textarea' => 'Textarea',
                'Timeline' => 'Timeline',
                'Toast' => 'Toast',
                'Toggle' => 'Toggle',
                'Tooltip' => 'Tooltip',
                'Typography' => 'Typography'
            ],
        ));

        $input->setOption('layout', select(
            label: 'Select your dashboard layout',
            options: [
                "DashKit" => "DashKit",
                "Adminify" => "Adminify",
                "DashPro" => "DashPro",
                "AdminGenius" => "AdminGenius",
                "AdminCraft" => "AdminCraft",
                "DashSpark" => "DashSpark",
                "PowerDash" => "PowerDash",
                "AdminMaster" => "AdminMaster",
                "ControlPanelX" => "ControlPanelX",
                "DashForge" => "DashForge"
            ]
        ));
    }


    /**
     * Prompt for missing input arguments using the returned questions.
     *
     * @return array<string, string>
     */
    protected function promptForMissingArgumentsUsing(): array
    {
        return [
            'framework' => fn() => select(
                label: 'The development stack that should be installed (react,vue)',
                options: [
                    'react' => 'react js',
                    'vue' => 'vue js'
                ],
                scroll: 6,
            ),
        ];
    }
}
