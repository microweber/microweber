<?php

namespace Modules\Ai\Agents;


use GuzzleHttp\Exception\RequestException;
use Modules\Ai\Agents\Support\JsonSchemaReflection;
use NeuronAI\Agent;
use NeuronAI\Chat\Messages\Message;
use NeuronAI\Chat\Messages\ToolCallMessage;
use NeuronAI\Chat\Messages\UserMessage;
use NeuronAI\Exceptions\AgentException;
use NeuronAI\Observability\Events\AgentError;
use NeuronAI\Observability\Events\InferenceStart;
use NeuronAI\Observability\Events\InferenceStop;
use NeuronAI\Providers\AIProviderInterface;
use NeuronAI\Providers\Anthropic\Anthropic;
use NeuronAI\Providers\Deepseek;
use NeuronAI\Providers\Gemini\Gemini;
use NeuronAI\Providers\Mistral;
use NeuronAI\Providers\Ollama\Ollama;
use NeuronAI\Providers\OpenAI\OpenAI;
use NeuronAI\StructuredOutput\JsonSchema;

class BaseAgent extends Agent
{

    protected string $instructions = 'Your are a helpful and friendly AI that can help with anything that is asked.';

    protected $providerName = null;
    protected $model = null;

    public function __construct(?string $providerName = null, ?string $model = null)
    {
        if ($providerName) {
            $this->providerName = $providerName;
        } else {
            $this->providerName = config('modules.ai.default_driver');
        }


        if ($model) {
            $this->model = $model;
        } else {
            $this->model = config('modules.ai.drivers.' . $this->providerName . '.model');
        }

    }

    protected function provider(): AIProviderInterface
    {
        // return an AI provider (Anthropic, OpenAI, Ollama, Gemini, etc.)


        if ($this->providerName == 'anthropic') {
            return new Anthropic(
                key: config('modules.ai.drivers.anthropic.api_key'),
                model: $this->model,
            );
        }

        if ($this->providerName == 'openai') {
            return new OpenAI(
                key: config('modules.ai.drivers.openai.api_key'),
                model: $this->model,
            );
        }

        if ($this->providerName == 'ollama') {
            return new Ollama(
                url: config('modules.ai.drivers.ollama.url'),
                model: $this->model,
            );
        }

        if ($this->providerName == 'deepseek') {
            return new Deepseek(
                key: config('modules.ai.drivers.deepseek.api_key'),
                model: $this->model,
            );
        }

        if ($this->providerName == 'mistral') {
            return new Mistral(
                key: config('modules.ai.drivers.mistral.api_key'),
                model: $this->model,
            );
        }


        if ($this->providerName == 'gemini') {
            return new Gemini(
                key: config('modules.ai.drivers.gemini.api_key'),
                model: $this->model,
            );
        }

    }
}
