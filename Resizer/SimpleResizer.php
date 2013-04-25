<?php

namespace IR\MediaBundle\Resizer;

use Gaufrette\File;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Imagine\Image\ImagineInterface;
use Imagine\Exception\InvalidArgumentException;
use IR\MediaBundle\Model\MediaInterface;

/**
 * Simple Resizer.
 *
 * @author Julien Kirsch <informatic.revolution@gmail.com>
 */
class SimpleResizer implements ResizerInterface
{   
    /**
     * @var \Imagine\Image\ImagineInterface $adapter
     */    
    protected $adapter;

    /**
     * @var string $mode
     */        
    protected $mode;
    
    /**
     * Constructor.
     * 
     * @param \Imagine\Image\ImagineInterface $adapter
     * @param string                          $mode
     */
    public function __construct(ImagineInterface $adapter, $mode)
    {
        $this->adapter = $adapter;
        $this->mode = $mode;
    }
    
    /**
     * {@inheritdoc}
     */
    public function resize(MediaInterface $media, File $in, File $out, $format, array $settings)
    {   
        if (!isset($settings['width'])) {
            throw new \RuntimeException(sprintf('Width parameter is missing in context "%s" for provider "%s"', $media->getContext(), $media->getProviderName()));
        }
        
        if (isset($settings['format']) && $settings['format'] === 'product') {
            $image = $this->adapter->load($in->getContent());
            $thumbnail = $image->thumbnail($this->getBox($media, $settings), 'inset'); 
        
            $size  = new Box($settings['width'], $settings['width']);
            $pImage = $this->adapter->create($size);    
            
            $widthSpace = $heightSpace = 0;
            
            if($thumbnail->getSize()->getWidth() > $thumbnail->getSize()->getHeight()) {
                $heightSpace = ($settings['width'] - $thumbnail->getSize()->getHeight())/2;
            }
            else {
                $widthSpace = ($settings['width'] - $thumbnail->getSize()->getWidth())/2;
            }            
            
            $pImage->paste($thumbnail, new \Imagine\Image\Point($widthSpace, $heightSpace));
            
            $content = $pImage->get($format, array('quality' => $settings['quality']));
        }
        else {
            $mode = isset($settings['format']) && $settings['format'] === 'photo' ? 'inset' : $this->mode;
            
            $image = $this->adapter->load($in->getContent());
            $thumbnail = $image->thumbnail($this->getBox($media, $settings), $mode); 
        
            $content = $thumbnail->get($format, array('quality' => $settings['quality']));
        }
        
        $out->setContent($content);
    }
    
    /**
     * {@inheritdoc}
     */
    public function getBox(MediaInterface $media, array $settings)
    {
        $size = new Box($media->getWidth(), $media->getHeight());

        if ($settings['width'] == null && $settings['height'] == null) {
            throw new \RuntimeException(sprintf('Width/Height parameter is missing in context "%s" for provider "%s". Please add at least one parameter.', $media->getContext(), $media->getProviderName()));
        }
        
        if ($settings['height'] == null) {
            $settings['height'] = (int) ($settings['width'] * $size->getHeight() / $size->getWidth());
        }
        
        if ($settings['width'] == null) {
            $settings['width'] = (int) ($settings['height'] * $size->getWidth() / $size->getHeight());
        }
        
        return $this->computeBox($media, $settings);
    }
    
    /**
     * @throws \Imagine\Exception\InvalidArgumentException
     *
     * @param \IR\MediaBundle\Model\MediaInterface $media
     * @param array                                $settings
     *
     * @return \Imagine\Image\Box
     */
    private function computeBox(MediaInterface $media, array $settings)
    {
        if ($this->mode !== ImageInterface::THUMBNAIL_INSET && $this->mode !== ImageInterface::THUMBNAIL_OUTBOUND) {
            throw new InvalidArgumentException('Invalid mode specified');
        }

        $size = new Box($media->getWidth(), $media->getHeight());

        $ratios = array(
            $settings['width'] / $size->getWidth(),
            $settings['height'] / $size->getHeight()
        );

        if ($this->mode === ImageInterface::THUMBNAIL_INSET) {
            $ratio = min($ratios);
        } else {
            $ratio = max($ratios);
        }
        return new Box($settings['width'], $settings['height']);
        
        //return $size->scale($ratio);
    }    
}