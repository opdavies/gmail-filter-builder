<?php

class GmailFilter
{
    /**
     * @param array
     */
    private $conditions = [];

    /**
     * @param array
     */
    private $labels = [];

    /**
     * @var bool
     */
    private $archive = false;

    /**
     * @var bool
     */
    private $spam = false;

    /**
     * @var bool
     */
    private $trash = false;

    /**
     * @var bool
     */
    private $neverSpam = false;

    public static function create()
    {
        return new static();
    }

    /**
     * @return boolean
     */
    public function isTrash() {
        return $this->trash;
    }

    /**
     * @return array
     */
    public function getConditions() {
        return $this->conditions;
    }

    /**
     * @return array
     */
    public function getLabels() {
        return $this->labels;
    }

    /**
     * @return boolean
     */
    public function isArchive() {
        return $this->archive;
    }

    /**
     * @return boolean
     */
    public function isSpam() {
        return $this->spam;
    }

    /**
     * @return boolean
     */
    public function isNeverSpam() {
        return $this->neverSpam;
    }

    /**
     * Condition based on words within the email.
     *
     * @param string $value
     *   The value to compare against.
     *
     * @return $this
     */
    public function contains($value) {
        return $this->condition('hasTheWord', $value);
    }

    /**
     * Condition based on words within the email.
     *
     * @param string $value
     *   The value to compare against.
     *
     * @return $this
     */
    public function has($value) {
        return $this->contains($value);
    }

    /**
     * Condition based on the subject.
     *
     * @param string $value
     *   The value to compare against.
     *
     * @return $this
     */
    public function subject($value) {
        return $this->condition('subject', $value);
    }

    /**
     * Add a label.
     *
     * @param string $label
     *   The label to assign.
     *
     * @return $this
     */
    public function label($label) {
        $this->labels[] = $label;

        return $this;
    }

    /**
     * Label and archive a message.
     *
     * @param string $label
     *   The label to assign.
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
    public function archive() {
        $this->archive = true;

        return $this;
    }

    /**
     * Mark as spam.
     *
     * @return $this
     */
    public function spam() {
        $this->spam = true;
        $this->neverSpam = false;

        return $this;
    }

    /**
     * Never mark as spam.
     *
     * @return $this
     */
    public function neverSpam() {
        $this->neverSpam = true;
        $this->spam = false;

        return $this;
    }

    /**
     * Who the email is from.
     *
     * @param array $values
     *   An array of names or email addresses for the sender.
     *
     * @return $this
     */
    public function from(array $values)
    {
        $this->condition('from', implode(' OR ', $values));

        return $this;
    }

    /**
     * Who the email is sent to.
     *
     * @param array $values
     *   An array of names or email addresses for the receiver.
     *
     * @return $this
     */
    public function to(array $values)
    {
        $this->condition('to', implode(' OR ', $values));

        return $this;
    }

    /**
     * Mark a message to be trashed.
     *
     * @return $this
     */
    public function trash()
    {
        $this->trash = TRUE;

        return $this;
    }

    /**
     * Add a condition.
     *
     * @param string $type
     *   The type of condition.
     * @param $value
     *   The value of the condition.
     *
     * @return $this
     */
    private function condition($type, $value)
    {
        $this->conditions[] = [$type, $value];

        return $this;
    }
}
