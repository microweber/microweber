<?php

namespace Modules\Ai\Services\Drivers;

interface AiImageServiceInterface extends AiServiceInterface
{


    /**
     * Generate an image based on the provided prompt and options.
     *
     * @param string $prompt The prompt for image generation.
     * @param array $options Additional options for image generation.
     * @return array The generated image URLs.
     */
    public function generateImage(string $prompt, array $options = []): array;

    /**
     * Get the name of this driver.
     *
     * @return string
     */
    public function getDriverName(): string;


}
