<?php

use Illuminate\Support\Facades\Schema;

class TranslationsTest extends TestCase
{
    /** @var \App\Domains\Repositories\I18nRepository */
    protected $i18n;

    /** @var \App\Domains\Repositories\TranslationsRepository */
    protected $translationsRepository;

    public function createApplication() {
        $app = parent::createApplication();

        if (!Schema::hasTable('translations'))
            Schema::create('translations');

        $this->i18n = $app->make('\App\Domains\Repositories\I18nRepository');
        $this->translationsRepository = $app->make('\App\Domains\Repositories\TranslationsRepository');

        return $app;
    }

    protected function setUp() {
        parent::setUp();
    }

    protected function tearDown() {
        $this->i18n->truncate();
        parent::tearDown();
    }

    public function testDefault() {
        $this->i18n->set('en.default.hello_world', 'Hello World!');
        $this->assertEquals('Hello World!', trans('hello_world'));

        $this->assertEquals(1, $this->translationsRepository->count());

        $t9n = $this->translationsRepository->get('app.en.default.hello_world');
        $this->assertNotNull($t9n);
        $this->assertInstanceOf('\App\Domains\Models\Translation', $t9n);

        $this->assertEquals('Hello World!', $t9n->target);

        //update
        $this->i18n->set('en.default.hello_world', 'Hello World Updated!');
        $this->assertEquals('Hello World Updated!', trans('hello_world'));

        $this->assertEquals(1, $this->translationsRepository->count());

        $t9n = $this->translationsRepository->get('app.en.default.hello_world');
        $this->assertNotNull($t9n);
        $this->assertInstanceOf('\App\Domains\Models\Translation', $t9n);

        $this->assertEquals('Hello World Updated!', $t9n->target);

        //delete
        $this->i18n->del('en.default.hello_world');
        $this->assertEquals('default.hello_world', trans('hello_world'));
        $this->assertEquals('default.hello_world', trans('default.hello_world', [], 'outcome'));
        $this->assertNotEquals('default.hello_world', trans('outcome.hello_world'));

        $this->assertEquals(0, $this->translationsRepository->count());

        $t9n = $this->translationsRepository->get('app.en.default.hello_world');
        $this->assertNull($t9n);


        $this->i18n->set('en.default.hello_world', 'Hello World!');
        $this->assertNotEquals('Hello World!', trans('outcome.hello_world'));
        $this->assertEquals('Hello World!', trans('default.hello_world', [], 'outcome'));
        $this->assertEquals('Hello World!', trans('hello_world'));

        $this->assertEquals(1, $this->translationsRepository->count());

        $t9n = $this->translationsRepository->get('app.en.default.hello_world');
        $this->assertNotNull($t9n);
        $this->assertInstanceOf('\App\Domains\Models\Translation', $t9n);

        $this->assertEquals('Hello World!', $t9n->target);
    }

    public function testDefaultLangs() {
        $this->i18n->set('en.default.hello_world', 'Hello World!');
        $this->i18n->set('pt.default.hello_world', 'Olá mundo!');
        $this->i18n->set('es.default.hello_world', 'Hola mondo!');

        $this->assertEquals(3, $this->translationsRepository->count());

        $this->assertEquals('Hello World!', trans('hello_world'));
        App::setLocale('es');
        $this->assertEquals('Hola mondo!', trans('hello_world'));
        Lang::setLocale('pt');
        $this->assertEquals('Olá mundo!', trans('hello_world'));

        Lang::setLocale('fr');
        $this->assertEquals('Hello World!', trans('hello_world'));

        $this->i18n->set('fr.default.hello_world', 'Bonjour tout le monde!');
        $this->assertEquals('Bonjour tout le monde!', trans('hello_world'));

        $this->assertEquals(4, $this->translationsRepository->count());

        $this->i18n->set('en.messages.hello_world', 'Hello World msg!');
        $this->i18n->set('pt.messages.hello_world', 'Olá Mundo msg!');
        $this->assertEquals('Hello World msg!', trans('messages.hello_world'));
        Lang::setLocale('pt');
        $this->assertEquals('Olá Mundo msg!', trans('messages.hello_world'));
        Lang::setLocale('fr');
        $this->assertEquals('Hello World msg!', trans('messages.hello_world'));
    }
}
