<?php

namespace Modules\Ai\Services\Drivers;

interface AiChatServiceInterface extends AiServiceInterface
{

    /**
     * Send a message to the chat service and get a response.
     *
     * @param array $messages
     * @param array $options
     * @return string|array
     */
    public function sendToChat(array $messages, array $options = []): string|array;

    /**
     * Get the name of the currently active AI driver.
     *
     * @return string
     */
    public function getActiveDriver(): string;


}
