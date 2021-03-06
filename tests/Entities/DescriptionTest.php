<?php namespace Arcanedev\SeoHelper\Tests\Entities;

use Arcanedev\SeoHelper\Entities\Description;
use Arcanedev\SeoHelper\Tests\TestCase;

/**
 * Class     DescriptionTest
 *
 * @package  Arcanedev\SeoHelper\Tests\Entities
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class DescriptionTest extends TestCase
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /** @var  \Arcanedev\SeoHelper\Contracts\Entities\Description */
    private $description;

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    public function setUp(): void
    {
        parent::setUp();

        $config            = $this->getDescriptionConfig();
        $this->description = new Description($config);
    }

    public function tearDown(): void
    {
        unset($this->description);

        parent::tearDown();
    }

    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_be_instantiated()
    {
        $expectations = [
            \Arcanedev\SeoHelper\Entities\Description::class,
            \Arcanedev\SeoHelper\Contracts\Renderable::class,
            \Arcanedev\SeoHelper\Contracts\Entities\Description::class,
        ];

        foreach ($expectations as $expected) {
            static::assertInstanceOf($expected, $this->description);
        }
    }

    /** @test */
    public function it_can_make()
    {
        $this->description = Description::make('Cool description about this package');

        $expectations = [
            \Arcanedev\SeoHelper\Entities\Description::class,
            \Arcanedev\SeoHelper\Contracts\Renderable::class,
            \Arcanedev\SeoHelper\Contracts\Entities\Description::class,
        ];

        foreach ($expectations as $expected) {
            static::assertInstanceOf($expected, $this->description);
        }
    }

    /** @test */
    public function it_can_get_default_description()
    {
        $content = $this->getDefaultContent();

        static::assertSame($content, $this->description->getContent());
    }

    /** @test */
    public function it_can_set_and_get_content()
    {
        $content = 'Cool description about this package';

        $this->description->set($content);

        static::assertSame($content, $this->description->getContent());
    }

    /** @test */
    public function it_can_set_and_get_max_length()
    {
        $max = 150;

        $this->description->setMax($max);

        static::assertSame($max, $this->description->getMax());
    }

    /** @test */
    public function it_must_throw_invalid_max_length_type()
    {
        $this->expectException(\Arcanedev\SeoHelper\Exceptions\InvalidArgumentException::class);
        $this->expectExceptionMessage('The description maximum length must be integer.');

        $this->description->setMax(null);
    }

    /** @test */
    public function it_must_throw_invalid_max_length_value()
    {
        $this->expectException(\Arcanedev\SeoHelper\Exceptions\InvalidArgumentException::class);
        $this->expectExceptionMessage('The description maximum length must be greater 0.');

        $this->description->setMax(0);
    }

    /** @test */
    public function it_can_render()
    {
        $this->description->set(
            $description = 'Cool description about this package'
        );

        $expected = '<meta name="description" content="'.$description.'">';

        static::assertHtmlStringEqualsHtmlString($expected, $this->description);
        static::assertHtmlStringEqualsHtmlString($expected, $this->description->render());
    }

    /** @test */
    public function it_can_render_empty_description()
    {
        $this->description->set('');

        static::assertEmpty($this->description->render());
        static::assertEmpty((string) $this->description);
    }

    /** @test */
    public function it_can_render_long_title()
    {
        $content = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, ullamco laboris aliquip commodo.';
        $max     = $this->getDefaultMax();

        $this->description->set($content)->setMax($max);

        $expected = '<meta name="description" content="'.str_limit($content, $max).'">';

        static::assertHtmlStringEqualsHtmlString($expected, $this->description);
        static::assertHtmlStringEqualsHtmlString($expected, $this->description->render());
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Get description config.
     *
     * @return array
     */
    private function getDescriptionConfig()
    {
        return $this->getSeoHelperConfig('description', []);
    }

    /**
     * Get default description content.
     *
     * @param  string  $default
     *
     * @return string
     */
    private function getDefaultContent($default = '')
    {
        return $this->getSeoHelperConfig('description.default', $default);
    }

    /**
     * Get default description max length.
     *
     * @param  int  $default
     *
     * @return int
     */
    private function getDefaultMax($default = 155)
    {
        return $this->getSeoHelperConfig('description.max', $default);
    }
}
