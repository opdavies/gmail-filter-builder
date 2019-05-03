<?php

namespace Opdavies\GmailFilterBuilder\Model;

use Tightenco\Collect\Support\Collection;

class Filter
{
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
        return $this->addCondition('hasTheWord', $value);
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function hasNot(string $value): self
    {
        return $this->addCondition('doesNotHaveTheWord', $value);
    }

    /**
     * @param string|array $values
     *
     * @return $this
     */
    public function from($values): self
    {
        return $this->addCondition('from', $values);
    }

    /**
     * @param string|array $values
     *
     * @return $this
     */
    public function to($values): self
    {
        return $this->addCondition('to', $values);
    }

    /**
     * @param string|array $values
     *
     * @return $this
     */
    public function subject($values): self
    {
        return $this->addCondition('subject', collect($values)->map('json_encode'));
    }

    /**
     * @return $this
     */
    public function hasAttachment(): self
    {
        return $this->addCondition('hasAttachment', 'true');
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
        return $this->has("list:{$value}");
    }

    /**
     * @return $this
     */
    public function excludeChats(): self
    {
        return $this->addCondition('excludeChats', 'true');
    }

    /**
     * @param string $label
     *
     * @return $this
     */
    public function label(string $label): self
    {
        return $this->addAction('label', $label);
    }

    /**
     * @return $this
     */
    public function archive(): self
    {
        return $this->addAction('shouldArchive', 'true');
    }

    /**
     * @param string $label
     *
     * @return $this
     */
    public function labelAndArchive(string $label): self
    {
        return $this->label($label)->archive();
    }

    /**
     * @return $this
     */
    public function spam(): self
    {
        return $this->addAction('shouldSpam', 'true')
            ->addAction('shouldNeverSpam', 'false');
    }

    /**
     * @return $this
     */
    public function neverSpam(): self
    {
        return $this->addAction('shouldSpam', 'false')
            ->addAction('shouldNeverSpam', 'true');
    }

    /**
     * @return $this
     */
    public function trash(): self
    {
        return $this->addAction('shouldTrash', 'true');
    }

    /**
     * @return $this
     */
    public function read(): self
    {
        return $this->addAction('markAsRead', 'true');
    }

    /**
     * @return $this
     */
    public function star(): self
    {
        return $this->addAction('shouldStar', 'true');
    }

    /**
     * @param string $to
     *
     * @return $this
     */
    public function forward(string $to): self
    {
        return $this->addAction('forwardTo', $to);
    }

    /**
     * @return $this
     */
    public function important(): self
    {
        return $this->addAction('shouldAlwaysMarkAsImportant', 'true');
    }

    /**
     * @return $this
     */
    public function notImportant(): self
    {
        return $this->addAction('shouldNeverMarkAsImportant', 'true');
    }

    /**
     * @param string $category
     *
     * @return $this
     */
    public function categorise(string $category): self
    {
        return $this->addAction('smartLabelToApply', $category);
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
                $property->getPropertyName() => $values->count() == 1
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

    private function addCondition(string $name, $value): self
    {
        $this->conditions->push(new FilterCondition($name, $value));

        return $this;
    }

    private function addAction(string $property, $value): self
    {
        $this->actions->push(new FilterAction($property, $value));

        return $this;
    }
}
