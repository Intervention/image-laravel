<?php

declare(strict_types=1);

namespace Intervention\Image\Laravel;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Response as  ResponseFactory;
use Intervention\Image\EncodedImage;
use Intervention\Image\Exceptions\DriverException;
use Intervention\Image\Exceptions\NotSupportedException;
use Intervention\Image\Exceptions\RuntimeException;
use Intervention\Image\Format;
use Intervention\Image\Image;

class ImageResponse
{
    /**
     * Image encoder options
     *
     * @var array<string, mixed>
     */
    protected array $options = [];

    /**
     * Create new ImageResponse instance
     *
     * @param Image|EncodedImage $image
     * @param null|Format $format
     * @param mixed ...$options
     * @return void
     */
    public function __construct(
        protected Image|EncodedImage $image,
        protected ?Format $format = null,
        mixed ...$options
    ) {
        $this->options = $options;
    }

    /**
     * Static factory method
     *
     * @param Image $image
     * @param null|Format $format
     * @param mixed ...$options
     * @throws NotSupportedException
     * @throws DriverException
     * @throws RuntimeException
     * @return Response
     */
    public static function make(Image $image, ?Format $format = null, mixed ...$options): Response
    {
        $generator = new self($image, $format, ...$options);

        return ResponseFactory::make(
            content: $generator->content(),
            headers: $generator->headers()
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
        if ($this->format) {
            return $this->format;
        }

        return Format::tryCreate($this->image->origin()->mediaType()) ?? Format::JPEG;
    }
}
