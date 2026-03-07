<?php

declare(strict_types=1);

namespace MicroweberPackages\AiTools\Base;

use MicroweberPackages\AiTools\Contracts\ToolInterface;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\Tool;
use NeuronAI\Tools\ToolProperty;
use NeuronAI\Workflow\WorkflowState;

/**
 * Abstract base class for all AI tools.
 *
 * Provides common functionality including:
 * - Authorization checking
 * - Input validation
 * - HTML formatting helpers
 * - Error/success handling
 * - Workflow state management
 */
abstract class BaseTool extends Tool implements ToolInterface
{
    protected string $domain = 'general';
    protected array $requiredPermissions = [];
    protected ?WorkflowState $state = null;
    protected ?int $maxTries = 500;

    public function __construct(
        string $name,
        string $description,
        protected array $dependencies = []
    ) {
        parent::__construct($name, $description);
    }

    /**
     * Execute the tool with the provided arguments.
     *
     * @param mixed ...$args
     * @return string
     */
    abstract public function __invoke(...$args): string;

    /**
     * Execute method (alias for __invoke for interface compliance).
     *
     * @param mixed ...$args
     * @return string
     */
    public function execute(...$args): string
    {
        return $this->__invoke(...$args);
    }

    public function setState(WorkflowState $state): void
    {
        $this->state = $state;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getDomain(): string
    {
        return $this->domain;
    }

    public function getRequiredPermissions(): array
    {
        return $this->requiredPermissions;
    }

    /**
     * Check if the current user is authorized to use this tool.
     *
     * @return bool
     */
    public function isAuthorized(): bool
    {
        return $this->authorize();
    }

    /**
     * Get the tool's input properties/schema.
     *
     * @return array
     */
    public function getProperties(): array
    {
        return $this->properties();
    }

    public function getMaxTries(): ?int
    {
        return $this->maxTries;
    }

    protected function authorize(): bool
    {
        // For now, return true. Override in subclasses for specific permission checks
        foreach ($this->requiredPermissions as $permission) {
            // Integration point: Check if user has permission
            // This should be implemented by the consuming application
            // return app('auth')->user()?->can($permission) ?? false;
        }
        return true;
    }

    protected function validateInput(array $input): bool
    {
        // Common validation logic - override in subclasses
        return true;
    }

    /**
     * Format data as an HTML table.
     *
     * @param array $data Array of rows (each row is array or object)
     * @param array $headers Column headers (optional)
     * @param string $emptyMessage Message to show when data is empty
     * @param string $tableClass Additional CSS classes for the table
     * @return string
     */
    protected function formatAsHtmlTable(
        array $data,
        array $headers = [],
        string $emptyMessage = 'No data found.',
        string $tableClass = ''
    ): string {
        if (empty($data)) {
            return '<div class="alert alert-info">' . htmlspecialchars($emptyMessage) . '</div>';
        }

        $html = '<div class="table-responsive mb-4">';
        $html .= '<table class="table table-striped table-bordered table-sm ' . $tableClass . '">';

        // Add headers if provided
        if (!empty($headers)) {
            $html .= '<thead class="table-light"><tr>';
            foreach ($headers as $key => $header) {
                $columnName = is_int($key) ? $header : $key;
                $html .= '<th>' . htmlspecialchars((string) $header) . '</th>';
            }
            $html .= '</tr></thead>';
        }

        $html .= '<tbody>';

        foreach ($data as $row) {
            $html .= '<tr>';
            if (is_array($row)) {
                foreach ($row as $cell) {
                    $html .= '<td>' . htmlspecialchars((string) $cell) . '</td>';
                }
            } elseif (is_object($row)) {
                foreach ($row as $property => $value) {
                    $html .= '<td>' . htmlspecialchars((string) $value) . '</td>';
                }
            } else {
                $html .= '<td>' . htmlspecialchars((string) $row) . '</td>';
            }
            $html .= '</tr>';
        }

        $html .= '</tbody></table></div>';

        return $html;
    }

    /**
     * Format data as a card grid.
     *
     * @param array $data Array of items
     * @param array $fields Field mapping ['property' => 'Label']
     * @param string $emptyMessage Message to show when empty
     * @return string
     */
    protected function formatAsCardGrid(
        array $data,
        array $fields = [],
        string $emptyMessage = 'No data found.'
    ): string {
        if (empty($data)) {
            return '<div class="alert alert-info">' . htmlspecialchars($emptyMessage) . '</div>';
        }

        $html = '<div class="row">';

        foreach ($data as $item) {
            $html .= '<div class="col-md-6 col-lg-4 mb-3">';
            $html .= '<div class="card">';
            $html .= '<div class="card-body">';

            if (is_array($item) || is_object($item)) {
                $itemArray = is_object($item) ? (array) $item : $item;
                foreach ($fields as $field => $label) {
                    $value = $itemArray[$field] ?? '';
                    $html .= '<p><strong>' . htmlspecialchars((string) $label) . ':</strong> ' . htmlspecialchars((string) $value) . '</p>';
                }
            } else {
                $html .= '<p>' . htmlspecialchars((string) $item) . '</p>';
            }

            $html .= '</div></div></div>';
        }

        $html .= '</div>';

        return $html;
    }

    /**
     * Handle an error condition.
     *
     * @param string $message Error message
     * @param bool $shouldFinish Whether this should end the workflow
     * @return string HTML error message
     */
    protected function handleError(string $message, bool $shouldFinish = false): string
    {
        if (isset($this->state)) {
            $this->state->set(static::class . '_finished', $shouldFinish);
        }

        return '<div class="alert alert-danger">' . htmlspecialchars($message) . '</div>';
    }

    /**
     * Handle a success condition.
     *
     * @param string $message Success message
     * @return string HTML success message
     */
    protected function handleSuccess(string $message): string
    {
        if (isset($this->state)) {
            $this->state->set(static::class . '_finished', true);
        }

        return '<div class="alert alert-success">' . htmlspecialchars($message) . '</div>';
    }

    /**
     * Format a monetary amount.
     *
     * @param float $amount
     * @param string $currency
     * @return string
     */
    protected function formatMoney(float $amount, string $currency = 'EUR'): string
    {
        return number_format($amount, 2) . ' ' . $currency;
    }

    /**
     * Format a date.
     *
     * @param \DateTimeInterface $date
     * @param string $format
     * @return string
     */
    protected function formatDate(\DateTimeInterface $date, string $format = 'Y-m-d H:i:s'): string
    {
        return $date->format($format);
    }
}
