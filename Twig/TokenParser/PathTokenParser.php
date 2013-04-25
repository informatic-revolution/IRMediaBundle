<?php

namespace IR\MediaBundle\Twig\TokenParser;

use IR\MediaBundle\Twig\Node\PathNode;

/**
 * @author Julien Kirsch <informatic.revolution@gmail.com>
 */
class PathTokenParser extends \Twig_TokenParser
{
    /**
     * @var string $extensionName
     */      
    protected $extensionName;

    /**
     * Constructor.
     * 
     * @param string $extensionName
     */
    public function __construct($extensionName)
    {
        $this->extensionName = $extensionName;
    }

    /**
     * {@inheritdoc}
     */
    public function parse(\Twig_Token $token)
    {
        $media = $this->parser->getExpressionParser()->parseExpression();

        $this->parser->getStream()->next();

        $format = $this->parser->getExpressionParser()->parseExpression();

        $this->parser->getStream()->expect(\Twig_Token::BLOCK_END_TYPE);

        return new PathNode($this->extensionName, $media, $format, $token->getLine(), $this->getTag());
    }

    /**
     * {@inheritdoc}
     */
    public function getTag()
    {
        return 'path';
    }
}