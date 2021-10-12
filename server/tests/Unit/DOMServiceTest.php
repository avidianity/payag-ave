<?php

namespace Tests\Unit;

use App\Exceptions\Services\DOMServiceException;
use App\Services\DOMService;
use DOMDocument;
use Mockery;
use PHPUnit\Framework\TestCase;
use Throwable;

class DOMServiceTest extends TestCase
{
    /**
     * @test
     */
    public function it_removes_script_tags_from_html()
    {
        $service = app(DOMService::class);

        $html = <<<HTML
<html>
    <head>
        <title>My Title</title>
    </head>
    <body>
        <div>Hello World</div>
        <script>console.log('Hello World! :)')</script>
        <script>alert('Mwehehehe')</script>
    </body>
</html>
HTML;

        $clean = $service->clean($html);

        $this->assertFalse($service->hasTag($clean, 'script'));
    }

    /**
     * @test
     */
    public function it_throws_exception_on_not_saving_html()
    {
        try {
            $html = <<<HTML
<html>
    <head>
        <title>My Title</title>
    </head>
    <body>
        <div>Hello World</div>
        <script>console.log('Hello World! :)')</script>
        <script>alert('Mwehehehe')</script>
    </body>
</html>
HTML;

            /**
             * @var \Mockery\MockInterface|\Mockery\LegacyMockInterface
             */
            $dom = Mockery::mock(DOMDocument::class);
            $dom->shouldReceive('loadHTML')->once()->andReturn(true);
            $dom->shouldReceive('getElementsByTagName')->andReturn([]);
            $dom->shouldReceive('saveHTML')->once()->andReturn(false);

            app()->instance(DOMDocument::class, $dom);

            app(DOMService::class)->clean($html);
        } catch (Throwable $exception) {
            $this->assertInstanceOf(DOMServiceException::class, $exception);
        }
    }

    /**
     * @test
     */
    public function it_throws_exception_on_not_loading_html()
    {
        try {
            $html = 'false html';

            /**
             * @var \Mockery\MockInterface|\Mockery\LegacyMockInterface
             */
            $dom = Mockery::mock(DOMDocument::class);
            $dom->shouldReceive('loadHTML')->once()->andReturn(false);

            app()->instance(DOMDocument::class, $dom);

            app(DOMService::class)->clean($html);
        } catch (Throwable $exception) {
            $this->assertInstanceOf(DOMServiceException::class, $exception);
        }
    }
}
