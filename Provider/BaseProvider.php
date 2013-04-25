<?php

namespace IR\MediaBundle\Provider;

use Gaufrette\Filesystem;
use IR\MediaBundle\Model\MediaInterface;
use IR\MediaBundle\CDN\CDNInterface;
use IR\MediaBundle\Resizer\ResizerInterface;
use IR\MediaBundle\Generator\GeneratorInterface;
use IR\MediaBundle\Thumbnail\ThumbnailInterface;

/**
 * Base Provider.
 *
 * @author Julien Kirsch <informatic.revolution@gmail.com>
 */
abstract class BaseProvider implements MediaProviderInterface
{
    /**
     * @var string $name
     */
    protected $name;  

    /**
     * @var \Gaufrette\Filesystem $filesystem
     */          
    protected $filesystem;    
    
    /**
     * @var \IR\MediaBundle\CDN\CDNInterface $cdn
     */        
    protected $cdn;    
    
    /**
     * @var \IR\MediaBundle\Generator\GeneratorInterface $pathGenerator
     */     
    protected $pathGenerator;    
    
    /**
     * @var \IR\MediaBundle\Thumbnail\ThumbnailInterface $thumbnail
     */    
    protected $thumbnail;    

    /**
     * @var \IR\MediaBundle\Resizer\ResizerInterface $resizer
     */      
    protected $resizer;    
    
    /**
     * @var array $templates
     */    
    protected $templates;    
    
    
    /**
     * Constructor.
     * 
     * @param string                                       $name
     * @param \Gaufrette\Filesystem                        $filesystem
     * @param \IR\MediaBundle\CDN\CDNInterface             $cdn
     * @param \IR\MediaBundle\Generator\GeneratorInterface $pathGenerator
     * @param \IR\MediaBundle\Thumbnail\ThumbnailInterface $thumbnail
     */
    public function __construct($name, Filesystem $filesystem, CDNInterface $cdn, GeneratorInterface $pathGenerator, ThumbnailInterface $thumbnail)
    {   
        $this->name          = $name;
        $this->filesystem    = $filesystem;
        $this->cdn           = $cdn;
        $this->pathGenerator = $pathGenerator;
        $this->thumbnail     = $thumbnail;
        $this->templates     = array();
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
    public function getFilesystem()
    {
        return $this->filesystem;
    }     
    
    /**
     * {@inheritdoc}
     */
    public function getCdn()
    {
        return $this->cdn;
    }     
    
    /**
     * {@inheritdoc}
     */
    public function getResizer()
    {
        return $this->resizer;
    }    
    
    /**
     * {@inheritdoc}
     */
    public function getTemplate($name)
    {
        return isset($this->templates[$name]) ? $this->templates[$name] : null;
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
    public function setResizer(ResizerInterface $resizer)
    {
        $this->resizer = $resizer;
    }    
    
    /**
     * {@inheritdoc}
     */
    public function setTemplates(array $templates)
    {
        $this->templates = $templates;
    }      
    
    /**
     * {@inheritdoc}
     */
    final public function transform(MediaInterface $media)
    {
        if (null === $media->getBinaryContent()) {
            return;
        }
        
        $this->doTransform($media);
    }    
    
    /**
     * @param \IR\MediaBundle\Model\MediaInterface $media
     *
     * @return void
     */
    abstract protected function doTransform(MediaInterface $media);    
    
    /**
     * {@inheritdoc}
     */
    public function generatePath(MediaInterface $media)
    {   
        return $this->pathGenerator->generatePath($media);
    }    
    
    /**
     * {@inheritdoc}
     */
    public function requireThumbnails()
    {
        return $this->getResizer() !== null;
    }     
    
    /**
     * {@inheritdoc}
     */
    public function generateThumbnails(MediaInterface $media)
    {
        $this->thumbnail->generate($this, $media);
    }    
    
    /**
     * {@inheritdoc}
     */
    public function preUpdate(MediaInterface $media)
    {   
        $media->setUpdatedAt(new \Datetime());
    }  
    
    /**
* {@inheritdoc}
*/
    public function preRemove(MediaInterface $media)
    {
        $path = $this->getReferenceImage($media);

        if ($this->getFilesystem()->has($path)) {
            $this->getFilesystem()->delete($path);
        }

        $this->thumbnail->delete($this, $media);
    }
    
    /**
* {@inheritdoc}
*/
    public function postRemove(MediaInterface $media)
    {
    
    }    
}