<?php
require 'vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Debugging: Output the API key to ensure it's set correctly
echo 'Google API Key: ' . $_ENV['GOOGLE_API_KEY'] . '<br>';

// Get the Google API key from environment variables
$googleApiKey = $_ENV['GOOGLE_API_KEY'];
?>