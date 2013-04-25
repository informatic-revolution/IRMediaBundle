<?php

namespace IR\MediaBundle\Model;

/**
 * Interface to be implemented by all Medias.
 *
 * @author Julien Kirsch <informatic.revolution@gmail.com>
 */
interface MediaInterface
{    
    /**
     * @return mixed
     */
    function getId();    
    
    /**
     * Gets name
     *
     * @return string $name
     */
    function getName();
    
    /**
     * Gets description
     *
     * @return string $description
     */
    function getDescription();
    
    /**
     * Gets provider_name
     *
     * @return string $providerName
     */
    function getProviderName();
    
    /**
     * Gets provider_reference
     *
     * @return string $providerReference
     */
    function getProviderReference();
    
    /**
     * Gets provider_metadata
     *
     * @return array $providerMetadata
     */
    function getProviderMetadata();
    
    /**
     * Gets a metadata value
     * 
     * @param string $name
     * @param null $default
     */
    function getMetadataValue($name, $default = null);    
    
    /**
     * Gets width
     *
     * @return integer $width
     */
    function getWidth();
    
    /**
     * Gets height
     *
     * @return integer $height
     */
    function getHeight();
    
    /**
     * Gets length
     *
     * @return float $length
     */
    function getLength();
    
    /**
     * Gets copyright
     *
     * @return string $copyright
     */
    function getCopyright();    
    
    /**
     * Gets authorName
     *
     * @return string $authorName
     */
    function getAuthorName();    
    
    /**
     * Gets context
     *
     * @return string $context
     */
    function getContext();
    
    /**
     * Gets content_type
     *
     * @return string $contentType
     */
    function getContentType();    
    
    /**
     * Gets extension
     * 
     * @return string
     */
    function getExtension();
        
    /**
     * Gets size
     *
     * @return integer $size
     */
    function getSize();
    
    /**
     * @return mixed
     */
    function getBinaryContent();    
     
    /**
     * Gets created_at
     *
     * @return \Datetime $createdAt
     */
    function getCreatedAt();    
    
    /**
     * Gets updated_at
     *
     * @return \Datetime $updatedAt
     */
    function getUpdatedAt();    
    
    /**
     * Sets name
     * 
     * @param string $name
     */
    function setName($name);    
    
    /**
     * Sets description
     *
     * @param string $description
     */
    function setDescription($description);    
    
    /**
     * Sets provider_name
     *
     * @param string $providerName
     */
    function setProviderName($providerName);    
    
    /**
     * Sets provider_reference
     *
     * @param string $providerReference
     */
    function setProviderReference($providerReference);    
    
    /**
     * Sets provider_metadata
     *
     * @param array $providerMetadata
     */
    function setProviderMetadata(array $providerMetadata = array());    
    
    /**
     * @param string $name
     * @param mixed $value
     */
    function setMetadataValue($name, $value);    
    
    /**
     * Remove a named data from the metadata
     *
     * @param string $name
     */
    function unsetMetadataValue($name);     
    
    /**
     * Sets width
     *
     * @param integer $width
     */
    function setWidth($width);    
    
    /**
     * Sets height
     *
     * @param integer $height
     */
    function setHeight($height);    
    
    /**
     * Sets length
     *
     * @param float $length
     */
    function setLength($length);    
    
    /**
     * Sets copyright
     *
     * @param string $copyright
     */
    function setCopyright($copyright);    
    
    /**
     * Sets authorName
     *
     * @param string $authorName
     */
    function setAuthorName($authorName);    
    
    /**
     * Sets context
     *
     * @param string $context
     */
    function setContext($context);    
        
    /**
     * Sets content_type
     *
     * @param string $contentType
     */
    function setContentType($contentType);    
    
    /**
     * Sets size
     *
     * @param integer $size
     */
    function setSize($size);
    
    /**
     * @param mixed $binaryContent
     */
    function setBinaryContent($binaryContent);    
    
    /**
     * Sets created_at
     *
     * @param \Datetime $createdAt
     */
    function setCreatedAt(\Datetime $createdAt = null);    
    
    /**
     * Sets updated_at
     *
     * @param \Datetime $updatedAt
     */
    function setUpdatedAt(\Datetime $updatedAt = null);     
}