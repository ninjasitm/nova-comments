<?php

namespace KirschbaumDevelopment\NovaComments\Tests;

class FrontendSourceTest extends TestCase
{
    public function test_the_nova_tool_uses_vue_three_and_nova_request_without_legacy_globals(): void
    {
        $entry = file_get_contents(__DIR__ . '/../resources/js/tool.js');
        $tool = file_get_contents(__DIR__ . '/../resources/js/components/Tool.vue');
        $comment = file_get_contents(__DIR__ . '/../resources/js/components/Comment.vue');

        $this->assertStringContainsString('Nova.booting((app)', $entry);
        $this->assertStringContainsString('app.component(', $entry);
        $this->assertStringContainsString('Nova.request()', $tool);
        $this->assertStringNotContainsString('axios', $tool);
        $this->assertStringNotContainsString('$toasted', $tool);
        $this->assertStringNotContainsString('_.find', $comment);
        $this->assertStringNotContainsString('moment', $comment);
        $this->assertStringContainsString('v-html="commentString"', $comment);
        $this->assertStringNotContainsString('/nova/resources/users/', $comment);
    }

    public function test_documentation_warns_that_comment_html_must_be_trusted(): void
    {
        $readme = file_get_contents(__DIR__ . '/../README.md');

        $this->assertStringContainsString('Trusted content', $readme);
        $this->assertStringContainsString('stored XSS', $readme);
    }

    public function test_pagination_uses_nova_five_camel_case_urls_for_navigation(): void
    {
        $tool = file_get_contents(__DIR__ . '/../resources/js/components/Tool.vue');

        $this->assertStringContainsString('nextPageUrl', $tool);
        $this->assertStringContainsString('prevPageUrl', $tool);
        $this->assertStringContainsString('@click="getComments(data.nextPageUrl)"', $tool);
        $this->assertStringContainsString('@click="getComments(data.prevPageUrl)"', $tool);
        $this->assertStringNotContainsString('next_page_url', $tool);
        $this->assertStringNotContainsString('prev_page_url', $tool);
    }
}
