<?php

namespace Opdavies\GmailFilterBuilder\Model;

use Tightenco\Collect\Support\Collection;

class Filter
{
    /** @var string */
    const SEPARATOR = '|';

    /**
     * @var array
     */
    private $properties = [];

    /** @var Collection */
    private $conditions;

    /** @var Collection */
    private $actions;

    public function __construct()
    {
        $this->conditions = collect();
        $this->actions = collect();
    }

    /**
     * @return static
     */
    public static function create(): self
    {
        return new static();
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function has(string $value): self
    {
        $this->conditions->push(new FilterCondition('hasTheWord', $value));

        return $this;
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function hasNot(string $value): self
    {
        $this->conditions->push(new FilterCondition('doesNotHaveTheWord', $value));

        return $this;
    }

    /**
     * @param string|array $values
     *
     * @return $this
     */
    public function from($values): self
    {
        $this->conditions->push(new FilterCondition('from', $values));

        return $this;
    }

    /**
     * @param string|array $values
     *
     * @return $this
     */
    public function to($values): self
    {
        $this->conditions->push(new FilterCondition('to', $values));

        return $this;
    }

    /**
     * @param string|array $values
     *
     * @return $this
     */
    public function subject($values): self
    {
        $this->conditions->push(new FilterCondition(
            'subject',
            collect($values)->map('json_encode')
        ));

        return $this;
    }

    /**
     * @return $this
     */
    public function hasAttachment(): self
    {
        $this->conditions->push(new FilterCondition('hasAttachment', 'true'));

        return $this;
    }

    /**
     * Filter a message if it was sent from a mailing list.
     *
     * @param string|array $value The mailing list address
     *
     * @return $this
     */
    public function fromList($value): self
    {
        $this->has("list:{$value}");

        return $this;
    }

    /**
     * @return $this
     */
    public function excludeChats(): self
    {
        $this->conditions->push(new FilterCondition('excludeChats', 'true'));

        return $this;
    }

    /**
     * @param string $label
     *
     * @return $this
     */
    public function label(string $label): self
    {
        $this->actions->push(new FilterAction('label', $label));

        return $this;
    }

    /**
     * @return $this
     */
    public function archive(): self
    {
        $this->actions->push(new FilterAction('shouldArchive', 'true'));

        return $this;
    }

    /**
     * @param string $label
     *
     * @return $this
     */
    public function labelAndArchive(string $label): self
    {
        $this->label($label)->archive();

        return $this;
    }

    /**
     * @return $this
     */
    public function spam(): self
    {
        $this->actions->push(new FilterAction('shouldSpam', 'true'));
        $this->actions->push(new FilterAction('shouldNeverSpam', 'false'));

        return $this;
    }

    /**
     * @return $this
     */
    public function neverSpam(): self
    {
        $this->actions->push(new FilterAction('shouldSpam', 'false'));
        $this->actions->push(new FilterAction('shouldNeverSpam', 'true'));

        return $this;
    }

    /**
     * @return $this
     */
    public function trash(): self
    {
        $this->actions->push(new FilterAction('shouldTrash', 'true'));

        return $this;
    }

    /**
     * @return $this
     */
    public function read(): self
    {
        $this->actions->push(new FilterAction('markAsRead', 'true'));

        return $this;
    }

    /**
     * @return $this
     */
    public function star(): self
    {
        $this->actions->push(new FilterAction('shouldStar', 'true'));

        return $this;
    }

    /**
     * @param string $to
     *
     * @return $this
     */
    public function forward(string $to): self
    {
        $this->actions->push(new FilterAction('forwardTo', $to));

        return $this;
    }

    /**
     * @return $this
     */
    public function important(): self
    {
        $this->actions->push(new FilterAction('shouldAlwaysMarkAsImportant', 'true'));

        return $this;
    }

    /**
     * @return $this
     */
    public function notImportant(): self
    {
        $this->actions->push(new FilterAction('shouldNeverMarkAsImportant', 'true'));

        return $this;
    }

    /**
     * @param string $category
     *
     * @return $this
     */
    public function categorise(string $category): self
    {
        $this->actions->push(new FilterAction('smartLabelToApply', $category));

        return $this;
    }

    /**
     * Return the filter properties as an array.
     *
     * @return array
     * @deprecated toArray()
     */
    public function getProperties(): array
    {
        return $this->properties;
    }

    /**
     * Return the filter properties as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->conditions->merge($this->actions)->mapWithKeys(function (FilterProperty $property) {
            $values = $property->getValues();

            return [
                $property->getProperty() => $values->count() == 1
                    ? $values->first()
                    : $values
            ];
        })->toArray();
    }

    public function getConditions(): Collection
    {
        return $this->conditions;
    }

    public function getActions(): Collection
    {
        return $this->actions;
    }
}
