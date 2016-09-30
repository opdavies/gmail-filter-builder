<?php

use Opdavies\Twig\Extensions\TwigBooleanStringExtension;

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
     * @var Twig_Environment
     */
    private $twig;

    /**
     * An array of filters.
     *
     * @var GmailFilter[]
     */
    private $filters = [];

    public function __construct(array $filters)
    {
        $this->twig = new Twig_Environment(
            new Twig_Loader_Filesystem(__DIR__.'/../templates')
        );

        $this->twig->addExtension(new TwigBooleanStringExtension());

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
     * @param GmailFilter[] $filters
     *   An array of filters to process.
     *
     * @return $this
     */
    public static function build(array $filters)
    {
        return new static($filters);
    }

    /**
     * @return string
     */
    private function generate()
    {
        ob_start();

        print $this->twig->render(
            'filters.xml.twig',
            [
                'name' => $this->name,
                'email' => $this->email,
                'filters' => $this->filters,
            ]
        );

        return ob_get_contents();
    }
}
