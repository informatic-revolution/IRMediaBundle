<?php

namespace IR\MediaBundle\Thumbnail;

use Symfony\Component\Routing\RouterInterface;
use IR\MediaBundle\Model\MediaInterface;
use IR\MediaBundle\Provider\MediaProviderInterface;

/**
 * LiipImagine Thumbnail.
 *
 * @author Julien Kirsch <informatic.revolution@gmail.com>
 */
class LiipImagineThumbnail implements ThumbnailInterface
{
    /**
     * @var \Symfony\Component\Routing\RouterInterface
     */
    protected $router;

    /**
     * @param \Symfony\Component\Routing\RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * {@inheritdoc}
     */
    public function generatePublicUrl(MediaProviderInterface $provider, MediaInterface $media, $format)
    {
        if ($format == 'reference') {
            $path = $provider->getReferenceImage($media);
        } else {
            $path = $this->router->generate(
                sprintf('_imagine_%s', $format),
                array('path' => sprintf('%s/%s_%s.jpg', $provider->generatePath($media), $media->getId(), $format))
            );
        }

        return $provider->getCdnPath($path, $media->getCdnIsFlushable());
    }

    /**
     * {@inheritdoc}
     */
    public function generatePrivateUrl(MediaProviderInterface $provider, MediaInterface $media, $format)
    {
        if ($format != 'reference') {
            throw new \RuntimeException('No private url for LiipImagineThumbnail');
        }

        $path = $provider->getReferenceImage($media);

        return $path;
    }

    /**
     * {@inheritdoc}
     */
    public function generate(MediaProviderInterface $provider, MediaInterface $media)
    {
        // nothing to generate, as generated on demand
        return;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(MediaProviderInterface $provider, MediaInterface $media)
    {
        // feature not available
        return;
    }
}