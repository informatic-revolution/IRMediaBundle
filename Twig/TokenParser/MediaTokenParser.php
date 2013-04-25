<?php

namespace IR\MediaBundle\Twig\TokenParser;

use IR\MediaBundle\Twig\Node\MediaNode;

/**
 * @author Julien Kirsch <informatic.revolution@gmail.com>
 */
class MediaTokenParser extends \Twig_TokenParser
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

        // attributes
        if ($this->parser->getStream()->test(\Twig_Token::NAME_TYPE, 'with')) {
            $this->parser->getStream()->next();

            $attributes = $this->parser->getExpressionParser()->parseExpression();
        } else {
            $attributes = new \Twig_Node_Expression_Array(array(), $token->getLine());
        }

        $this->parser->getStream()->expect(\Twig_Token::BLOCK_END_TYPE);

        return new MediaNode($this->extensionName, $media, $format, $attributes, $token->getLine(), $this->getTag());
    }

    /**
     * {@inheritdoc}
     */
    public function getTag()
    {
        return 'media';
    }
}