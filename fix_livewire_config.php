<?php

// Fix Livewire config to match the actual namespace
$configPath = 'config/livewire.php';
$content = file_get_contents($configPath);

// Replace the class_namespace
$content = str_replace(
    "'class_namespace' => 'App\\\\Livewire',",
    "'class_namespace' => 'App\\\\Http\\\\Livewire',",
    $content
);

file_put_contents($configPath, $content);
echo "Livewire config updated successfully!\n";
