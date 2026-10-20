<?php

declare(strict_types=1);

namespace Arcanedev\Support\Database;

use Illuminate\Database\Eloquent\Model;

/**
 * Class     PrefixedModel
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class PrefixedModel extends Model
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /**
     * The table prefix.
     *
     * @var string|null
     */
    protected $prefix;

    /* -----------------------------------------------------------------
     |  Getters & Setters
     | -----------------------------------------------------------------
     */

    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getTable()
    {
        return $this->getPrefix().parent::getTable();
    }

    /**
     * Get the prefix table associated with the model.
     *
     * @return string
     */
    public function getPrefix(): string
    {
        return $this->isPrefixed() ? $this->prefix : '';
    }

    /**
     * Set the prefix table associated with the model.
     *
     * @param  string|null  $prefix
     *
     * @return $this
     */
    public function setPrefix(?string $prefix)
    {
        $this->prefix = $prefix;

        return $this;
    }

    /* -----------------------------------------------------------------
     |  Check Methods
     | -----------------------------------------------------------------
     */

    /**
     * Check if table is prefixed.
     *
     * @return bool
     */
    public function isPrefixed(): bool
    {
        return ! is_null($this->prefix);
    }
}
