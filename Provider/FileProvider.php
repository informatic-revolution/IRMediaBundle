<?php

namespace IR\MediaBundle\Provider;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Gaufrette\Filesystem;
use IR\MediaBundle\Model\MediaInterface;
use IR\MediaBundle\CDN\CDNInterface;
use IR\MediaBundle\Resizer\ResizerInterface;
use IR\MediaBundle\Generator\GeneratorInterface;
use IR\MediaBundle\Thumbnail\ThumbnailInterface;
use Symfony\Component\Validator\ExecutionContext;
/**
 * File Provider.
 *
 * @author Julien Kirsch <informatic.revolution@gmail.com>
 */
class FileProvider extends BaseProvider
{
    
    /**
     * @var array $allowedExtensions
     */   
    protected $allowedExtensions;

    /**
     * @var array $allowedMimeTypes
     */     
    protected $allowedMimeTypes;    
    
    /**
     * Constructor.
     * 
     * @param string                                       $name
     * @param \Gaufrette\Filesystem                        $filesystem
     * @param \IR\MediaBundle\CDN\CDNInterface             $cdn
     * @param \IR\MediaBundle\Generator\GeneratorInterface $pathGenerator
     * @param \IR\MediaBundle\Thumbnail\ThumbnailInterface $thumbnail
     * @param array                                        $allowedExtensions
     * @param array                                        $allowedMimeTypes
     */
    public function __construct($name, Filesystem $filesystem, CDNInterface $cdn, GeneratorInterface $pathGenerator, ThumbnailInterface $thumbnail, array $allowedExtensions = array(), array $allowedMimeTypes = array())
    {
        parent::__construct($name, $filesystem, $cdn, $pathGenerator, $thumbnail);

        $this->allowedExtensions = $allowedExtensions;
        $this->allowedMimeTypes = $allowedMimeTypes;
    }    
          
    /**
     * {@inheritdoc}
     */
    protected function doTransform(MediaInterface $media)
    {
        $this->fixBinaryContent($media);
        $this->fixFilename($media);

        // this is the name used to store the file
        if (!$media->getProviderReference()) {
            $media->setProviderReference($this->generateReferenceName($media));
        }
        
        if ($media->getBinaryContent()) {
            $media->setContentType($media->getBinaryContent()->getMimeType());
            $media->setSize($media->getBinaryContent()->getSize());
        }
    }
    
    /**
     * @throws \RuntimeException
     *
     * @param  \IR\MediaBundle\Model\MediaInterface $media
     * @return void
     */
    protected function fixBinaryContent(MediaInterface $media)
    {
        if ($media->getBinaryContent() === null) {
            return;
        }

        // if the binary content is a filename => convert to a valid File
        if (!$media->getBinaryContent() instanceof File) {
            
            if (!is_file($media->getBinaryContent())) {
                // A verifier pour l'upload à distance
                $tmpFile = tempnam(sys_get_temp_dir(), 'pvt');
                file_put_contents($tmpFile, file_get_contents($media->getBinaryContent()));
                $media->setBinaryContent($tmpFile);
                
                //throw new \RuntimeException('The file does not exist : ' . $media->getBinaryContent());
            }

            $binaryContent = new File($media->getBinaryContent());
            
            $media->setBinaryContent($binaryContent);
        }
    }    
    
    /**
     * @throws \RuntimeException
     *
     * @param  \IR\MediaBundle\Model\MediaInterface $media
     * @return void
     */
    protected function fixFilename(MediaInterface $media)
    {   
        if ($media->getBinaryContent() instanceof UploadedFile) {
            $media->setName($media->getBinaryContent()->getClientOriginalName());
            $media->setMetadataValue('filename', $media->getBinaryContent()->getClientOriginalName());
        } else if ($media->getBinaryContent() instanceof File) {
            $media->setName($media->getBinaryContent()->getBasename());
            $media->setMetadataValue('filename', $media->getBinaryContent()->getBasename());
        }
        
        // this is the original name
        if (!$media->getName()) {
            throw new \RuntimeException('Please define a valid media\'s name');
        }
    }
    
    /**
     * @param  \IR\MediaBundle\Model\MediaInterface $media
     * @return string
     */
    protected function generateReferenceName(MediaInterface $media)
    {
        return sha1($media->getName() . rand(11111, 99999)) . '.' . $media->getBinaryContent()->guessExtension();
    }     
    
    /**
     * {@inheritdoc}
     */
    public function getReferenceImage(MediaInterface $media)
    {   
        return sprintf('%s/%s',
            $this->generatePath($media),
            $media->getProviderReference()
        );
    }    
    
    /**
     * {@inheritdoc}
     */
    public function getReferenceFile(MediaInterface $media)
    {   
        return $this->getFilesystem()->get($this->getReferenceImage($media), true);
    }    
    
    /**
     * {@inheritdoc}
     */
    public function generatePublicUrl(MediaInterface $media, $format)
    {   // TO WORK
        if ($format == 'reference') {
            $path = $this->getReferenceImage($media);
        } else {
            $path = sprintf('media_bundle/images/files/%s/file.png', $format);
        }

        return $this->getCdn()->getPath($path);
    }    
    
    /**
     * {@inheritdoc}
     */
    public function generatePrivateUrl(MediaInterface $media, $format)
    {
        return false;
    }    
    
    /**
     * Sets the file contents for an image
     *
     * @param \IR\MediaBundle\Model\MediaInterface $media
     * @param string $contents path to contents, defaults to MediaInterface BinaryContent
     *
     * @return void
     */
    protected function setFileContents(MediaInterface $media, $contents = null)
    {   
        $file = $this->getFilesystem()->get($this->getReferenceImage($media), true);
        
        if (null === $contents) {
            $contents = $media->getBinaryContent()->getRealPath();
        }

        $file->setContent(file_get_contents($contents));
    }     
     
    /**
     * {@inheritdoc}
     */
    public function postPersist(MediaInterface $media)
    {
        if ($media->getBinaryContent() === null) {
            return;
        }
        
        $this->setFileContents($media);
        $this->generateThumbnails($media);
    }    
    
    /**
     * {@inheritdoc}
     */
    public function postUpdate(MediaInterface $media)
    {   
        if (!$media->getBinaryContent() instanceof \SplFileInfo) {
            return;
        }
        
        // Delete the current file from the FS
        $oldMedia = clone $media;
        //$oldMedia->setProviderReference($media->getPreviousProviderReference());

        $path = $this->getReferenceImage($oldMedia);
        
        if ($this->getFilesystem()->has($path)) {
            $this->getFilesystem()->delete($path);
        }
        $this->thumbnail->delete($this, $media);
     
        $this->fixBinaryContent($media);

        $this->setFileContents($media);

        $this->generateThumbnails($media);
    }    
    
    /**
     * {@inheritdoc}
     */
    public function validate(MediaInterface $media, ExecutionContext $context)
    {   
        if (!$media->getBinaryContent() instanceof \SplFileInfo) {
            return;
        }

        if ($media->getBinaryContent() instanceof UploadedFile) {
            $fileName = $media->getBinaryContent()->getClientOriginalName();
        } else if ($media->getBinaryContent() instanceof File) {
            $fileName = $media->getBinaryContent()->getFilename();
        } else {
            throw new \RuntimeException(sprintf('Invalid binary content type: %s', get_class($media->getBinaryContent())));
        }
        
        if (!in_array(strtolower(pathinfo($fileName, PATHINFO_EXTENSION)), $this->allowedExtensions)) {
            $context->addViolation('Invalid extensions', array(), '');
        }

        if (!in_array($media->getBinaryContent()->getMimeType(), $this->allowedMimeTypes)) {
            $context->addViolation('Invalid mime type : ' . $media->getBinaryContent()->getMimeType(), array(), '');
        }
    }    
}