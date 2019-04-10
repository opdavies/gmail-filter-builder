<?php

namespace Opdavies\GmailFilterBuilder\Model;

class Filter
{
    /** @var string */
    const SEPARATOR = '|';

    /**
     * @var array
     */
    private $properties = [];

    /**
     * @return static
     */
    public static function create()
    {
        return new static();
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function has($value)
    {
        $this->properties['hasTheWord'] = $value;

        return $this;
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function hasNot($value)
    {
        $this->properties['doesNotHaveTheWord'] = $value;

        return $this;
    }

    /**
     * @param string|array $values
     *
     * @return $this
     */
    public function from($values)
    {
        if (!empty($values)) {
            $this->properties['from'] = collect($values)->map(function ($value) {
                return trim($value);
            })->all();
        }

        return $this;
    }

    /**
     * @param string|array $values
     *
     * @return $this
     */
    public function to($values)
    {
        if (!empty($values)) {
            $this->properties['to'] = collect($values)->map(function ($value) {
                return trim($value);
            })->all();
        }

        return $this;
    }

    /**
     * @param string|array $values
     *
     * @return $this
     */
    public function subject($values)
    {
        $this->properties['subject'] = collect($values)->map(function ($value) {
            return json_encode($value);
        })->implode('|');

        return $this;
    }

    /**
     * @return $this
     */
    public function hasAttachment()
    {
        $this->properties['hasAttachment'] = 'true';

        return $this;
    }

    /**
     * Filter a message if it was sent from a mailing list.
     *
     * @param string|array $value The mailing list address
     *
     * @return $this
     */
    public function fromList($value)
    {
        $value = collect($value)->implode(self::SEPARATOR);
        $this->has("list:{$value}");

        return $this;
    }

    /**
     * @return $this
     */
    public function excludeChats()
    {
        $this->properties['excludeChats'] = 'true';

        return $this;
    }

    /**
     * @param string $label
     *
     * @return $this
     */
    public function label($label)
    {
        $this->properties['label'] = $label;

        return $this;
    }

    /**
     * @return $this
     */
    public function archive()
    {
        $this->properties['shouldArchive'] = 'true';

        return $this;
    }

    /**
     * @param string $label
     *
     * @return $this
     */
    public function labelAndArchive($label)
    {
        $this->label($label)->archive();

        return $this;
    }

    /**
     * @return $this
     */
    public function spam()
    {
        $this->properties['shouldSpam'] = 'true';
        $this->properties['shouldNeverSpam'] = 'false';

        return $this;
    }

    /**
     * @return $this
     */
    public function neverSpam()
    {
        $this->properties['shouldSpam'] = 'false';
        $this->properties['shouldNeverSpam'] = 'true';

        return $this;
    }

    /**
     * @return $this
     */
    public function trash()
    {
        $this->properties['shouldTrash'] = 'true';

        return $this;
    }

    /**
     * @return $this
     */
    public function read()
    {
        $this->properties['markAsRead'] = 'true';

        return $this;
    }

    /**
     * @return $this
     */
    public function star()
    {
        $this->properties['shouldStar'] = 'true';

        return $this;
    }

    /**
     * @param string $to
     *
     * @return $this
     */
    public function forward($to)
    {
        $this->properties['forwardTo'] = $to;

        return $this;
    }

    /**
     * @return $this
     */
    public function important()
    {
        $this->properties['shouldAlwaysMarkAsImportant'] = 'true';

        return $this;
    }

    /**
     * @return $this
     */
    public function notImportant()
    {
        $this->properties['shouldNeverMarkAsImportant'] = 'true';

        return $this;
    }

    /**
     * @param string $category
     *
     * @return $this
     */
    public function categorise($category)
    {
        $this->properties['smartLabelToApply'] = $category;

        return $this;
    }

    /**
     * Return the filter properties as an array.
     *
     * @return array
     * @deprecated toArray()
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * Return the filter properties as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->properties;
    }
}
