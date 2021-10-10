<?php

namespace Tests\Unit;

use App\Services\DOMService;
use PHPUnit\Framework\TestCase;

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
}
