<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class PluginManager
{
    protected array $plugins = [];

    /**
     * Register a plugin with its configuration.
     */
    public function registerPlugin(string $pluginName, array $config)
    {
        $this->plugins[$pluginName] = $config;
        Log::info("Plugin '{$pluginName}' registered with config: " . json_encode($config));
    }

    /**
     * Execute a hook for all registered plugins.
     */
    public function executeHook(string $hook, array $data)
    {
        Log::info("Executing hook '{$hook}' with data: " . json_encode($data));
        // In a real system, you would call a method on each plugin class.
        // For this demo, we just log the event.
        foreach ($this->plugins as $pluginName => $config) {
            Log::info("  -> Hook '{$hook}' triggered for plugin '{$pluginName}'");
        }
    }
}
