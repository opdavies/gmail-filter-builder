<?php

class GmailFilterBuilder
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $email;

    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * An array of filters.
     *
     * @var GmailFilter[]
     */
    private $filters = [];

    public function __construct($name, $email, $filters)
    {
        $this->name = $name;
        $this->email = $email;

        $this->twig = new Twig_Environment(
            new Twig_Loader_Filesystem(__DIR__ . '/../templates')
        );

        $this->filters = $filters;

        return $this->generate();
    }

    public function __toString()
    {
        return $this->generate();
    }

    /**
     * Build Gmail filters.
     *
     * @param string $name
     *   The author name.
     * @param string $email
     *   The author email address.
     * @param GmailFilter[] $filters
     *   An array of filters to process.
     *
     * @return $this
     */
    public static function build($name, $email, array $filters)
    {
        return new static($name, $email, $filters);
    }

  /**
   * @return string
   */
    private function generate()
    {
        return $this->twig->render(
            'filters.xml.twig',
            [
                'name' => $this->name,
                'email' => $this->email,
                'filters' => $this->filters,
            ]
        );
    }
}
