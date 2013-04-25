<?php

namespace IR\MediaBundle\Model;

/**
* Default Media Model.
*
* @author Julien Kirsch <informatic.revolution@gmail.com>
*/
abstract class Media implements MediaInterface
{
    /**
     * @var string $name
     */
    protected $name;
    
    /**
     * @var text $description
     */
    protected $description;
    
    /**
     * @var string $provider_name
     */
    protected $providerName;
    
    /**
     * @var string $provider_reference
     */
    protected $providerReference;
    
    /**
     * @var array $provider_metadata
     */
    protected $providerMetadata;
    
    /**
     * @var integer $width
     */
    protected $width;

    /**
     * @var integer $height
     */
    protected $height;

    /**
     * @var decimal $length
     */
    protected $length;

    /**
     * @var string $copyright
     */
    protected $copyright;

    /**
     * @var string $author_name
     */
    protected $authorName;

    /**
     * @var string $context
     */
    protected $context;
    
    /**
     * @var varchar $content_type
     */
    protected $contentType;

    /**
     * @var integer $size
     */
    protected $size;    

    /**
     * @var mixed $binaryContent
     */    
    protected $binaryContent;    
    
    /**
     * @var datetime $created_at
     */
    protected $createdAt;     
    
    /**
     * @var datetime $updated_at
     */
    protected $updatedAt;

    
    /**
     * Constructor.
     */    
    public function __constructor()
    {
        $this->providerMetadata = array();
        $this->context = 'default';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getDescription()
    {
        return $this->description;
    }    
    
    /**
     * {@inheritdoc}
     */
    public function getProviderName()
    {
        return $this->providerName;
    }    
        
    /**
     * {@inheritdoc}
     */
    public function getProviderReference()
    {   
        return $this->providerReference;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getProviderMetadata()
    {
        return $this->providerMetadata;
    }    
    
    /**
     * {@inheritdoc}
     */
    public function getMetadataValue($name, $default = null)
    {
        $metadata = $this->getProviderMetadata();

        return isset($metadata[$name]) ? $metadata[$name] : $default;
    }    
    
    /**
     * {@inheritdoc}
     */
    public function getWidth()
    {
        return $this->width;
    }    
    
    /**
     * {@inheritdoc}
     */
    public function getHeight()
    {
        return $this->height;
    }    
    
    /**
     * {@inheritdoc}
     */
    public function getLength()
    {
        return $this->length;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getCopyright()
    {
        return $this->copyright;
    }    
    
    /**
     * {@inheritdoc}
     */
    public function getAuthorName()
    {
        return $this->authorName;
    }    
    
    /**
     * {@inheritdoc}
     */
    public function getContext()
    {
        return $this->context;
    }    
    
    /**
     * {@inheritdoc}
     */
    public function getContentType()
    {
        return $this->contentType;
    }    
    
    /**
     * {@inheritdoc}
     */
    public function getExtension()
    {
        return pathinfo($this->getProviderReference(), PATHINFO_EXTENSION);
    }    
    
    /**
     * {@inheritdoc}
     */
    public function getSize()
    {
        return $this->size;
    }    
    
    /**
     * {@inheritdoc}
     */
    public function getBinaryContent()
    {
        return $this->binaryContent;
    }    
      
    /**
     * {@inheritdoc}
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }    
        
    /**
     * {@inheritdoc}
     */
    public function setName($name)
    {
        $this->name = $name;
    }
    
    /**
     * {@inheritdoc}
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }
    
    /**
     * {@inheritdoc}
     */
    public function setProviderName($providerName)
    {
        $this->providerName = $providerName;
    } 
    
    /**
     * {@inheritdoc}
     */
    public function setProviderReference($providerReference)
    {
        $this->providerReference = $providerReference;
    }
    
    /**
     * {@inheritdoc}
     */
    public function setProviderMetadata(array $providerMetadata = array())
    {
        $this->providerMetadata = $providerMetadata;
    }
    
    /**
     * {@inheritdoc}
     */
    public function setMetadataValue($name, $value)
    {
        $metadata = $this->getProviderMetadata();
        $metadata[$name] = $value;
        $this->setProviderMetadata($metadata);
    }

    /**
     * {@inheritdoc}
     */
    public function unsetMetadataValue($name)
    {
        $metadata = $this->getProviderMetadata();
        unset($metadata[$name]);
        $this->setProviderMetadata($metadata);
    }    
    
    /**
     * {@inheritdoc}
     */
    public function setWidth($width)
    {
        $this->width = $width;
    }
    
    /**
     * {@inheritdoc}
     */
    public function setHeight($height)
    {
        $this->height = $height;
    }
    
    /**
     * {@inheritdoc}
     */
    public function setLength($length)
    {
        $this->length = $length;
    }
    
    /**
     * {@inheritdoc}
     */
    public function setCopyright($copyright)
    {
        $this->copyright = $copyright;
    } 
    
    /**
     * {@inheritdoc}
     */
    public function setAuthorName($authorName)
    {
        $this->authorName = $authorName;
    }
    
    /**
     * {@inheritdoc}
     */
    public function setContext($context)
    {
        $this->context = $context;
    }  
    
    /**
     * {@inheritdoc}
     */
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;
    }    
    
    /**
     * {@inheritdoc}
     */
    public function setSize($size)
    {
        $this->size = $size;
    }    
    
    /**
     * {@inheritdoc}
     */
    public function setBinaryContent($binaryContent)
    {
        $this->binaryContent = $binaryContent;
    }    
    
    /**
     * {@inheritdoc}
     */
    public function setCreatedAt(\DateTime $createdAt = null)
    {
        $this->createdAt = $createdAt;
    }
    
    /**
     * {@inheritdoc}
     */
    public function setUpdatedAt(\DateTime $updatedAt = null)
    {
        $this->updatedAt = $updatedAt;
    }
    
    /**
     * Gets the Media string representation.
     *
     * @return string
     */    
    public function __toString()
    {
        return $this->getName() ?: 'n/a';
    }    
}

    
