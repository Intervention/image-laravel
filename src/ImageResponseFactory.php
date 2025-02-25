<?php

declare(strict_types=1);

namespace Intervention\Image\Laravel;

use Illuminate\Http\Response;
use Intervention\Image\Exceptions\DriverException;
use Intervention\Image\Exceptions\NotSupportedException;
use Intervention\Image\Exceptions\RuntimeException;
use Intervention\Image\FileExtension;
use Intervention\Image\Format;
use Intervention\Image\Image;
use Intervention\Image\MediaType;

class ImageResponseFactory
{
    /**
     * Image encoder options
     *
     * @var array<string, mixed>
     */
    protected array $options = [];

    /**
     * Create new ImageResponseFactory instance
     *
     * @param Image $image
     * @param null|string|Format|MediaType|FileExtension $format
     * @param mixed ...$options
     * @return void
     */
    public function __construct(
        protected Image $image,
        protected null|string|Format|MediaType|FileExtension $format = null,
        mixed ...$options
    ) {
        $this->options = $options;
    }

    /**
     * Static factory method to create HTTP response directly
     *
     * @param Image $image
     * @param null|string|Format|MediaType|FileExtension $format
     * @param mixed ...$options
     * @throws NotSupportedException
     * @throws DriverException
     * @throws RuntimeException
     * @return Response
     */
    public static function make(
        Image $image,
        null|string|Format|MediaType|FileExtension $format = null,
        mixed ...$options,
    ): Response {
        return (new self($image, $format, ...$options))->response();
    }

    /**
     * Create HTTP response
     *
     * @throws NotSupportedException
     * @throws DriverException
     * @throws RuntimeException
     * @return Response
     */
    public function response(): Response
    {
        return new Response(
            content: $this->content(),
            headers: $this->headers()
        );
    }

    /**
     * Read image contents
     *
     * @throws NotSupportedException
     * @throws DriverException
     * @throws RuntimeException
     * @return string
     */
    private function content(): string
    {
        return (string) $this->image->encodeByMediaType(
            $this->format()->mediaType(),
            ...$this->options
        );
    }

    /**
     * Return HTTP response headers to be attached in the image response
     *
     * @return array
     */
    private function headers(): array
    {
        return [
            'Content-Type' => $this->format()->mediaType()->value
        ];
    }

    /**
     * Determine the target format of the image in the HTTP response
     *
     * @return Format
     */
    private function format(): Format
    {
        if ($this->format instanceof Format) {
            return $this->format;
        }

        if (($this->format instanceof MediaType)) {
            return $this->format->format();
        }

        if ($this->format instanceof FileExtension) {
            return $this->format->format();
        }

        if (is_string($this->format)) {
            return Format::create($this->format);
        }

        // target format is undefined (null) at this point:
        // try to extract the original image format or use jpeg by default.
        return Format::tryCreate($this->image->origin()->mediaType()) ?? Format::JPEG;
    }
}
