<?php

namespace Tests\Unit;

use Illuminate\Container\Container;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\View\Factory;
use PHPUnit_Framework_MockObject_MockObject;
use Prophecy\Exception\Doubler\MethodNotFoundException;
use Savich\FormBuilder\FormBuilder;
use Symfony\Component\Debug\Exception\FatalThrowableError;
use Tests\Fixtures\Form;
use Tests\Fixtures\UserModel;
use Tests\TestCase;

class FormTest extends TestCase
{
    public function testType()
    {
        /** @var Form $form */
        $form = $this->getForm();

        $this->assertSame($form->type('my_type'), $form);
        $this->assertEquals($form->type, 'my_type');
    }

    public function testVars()
    {
        /** @var Form $form */
        $form = $this->getForm();

        $form->my_var = 5;
        $this->assertEquals(5, $form->my_var);

        $form->vars('my_var', 6);
        $this->assertEquals(6, $form->my_var);

        $form->vars(['my_var' => 7, 'new_var' => 10]);
        $this->assertEquals(7, $form->my_var);
        $this->assertEquals(10, $form->new_var);
    }

    public function testRoute()
    {
        /** @var Form $form */
        $form = $this->getForm();

        $this->assertEquals('create.route', $form->route());
        $this->assertInternalType('array', $form->route('param'));

        $form->type('another');
        $this->assertNull($form->route());
        $this->assertNull($form->route('param'));
    }

    public function testMethod()
    {
        /** @var Form $form */
        $form = $this->getForm();

        $this->assertEquals('POST', $form->method());

        $form->type('edit');
        $this->assertEquals('PUT', $form->method());

        $form->type('delete');
        $this->assertNull($form->method());
    }

    public function testModel()
    {
        /** @var Form $form */
        $form = $this->getForm();

        $this->assertNull($form->model());
        $this->assertNull($form->model('property'));

        $user = new UserModel(['property' => 'property_value']);

        $this->assertEquals($user, $form->model($user));
        $this->assertEquals('property_value', $form->model('property'));
    }

    public function testInitializeRequest()
    {
        /** @var Form $form */
        $form = $this->getForm();

        $request = app(Request::class);

        $form->initializeRequest($request, app());

        $this->assertThat($form->request(), $this->logicalOr(
            $this->isInstanceOf(Request::class),
            $this->isNull()
        ));
    }

    public function testMagic()
    {
        /** @var Form $form */
        $form = $this->getForm();

        $form->edit();
        $this->assertEquals('edit', $form->type);

        try {
            $form->delete();
            $this->fail('Expected MethodNotFoundException');
        } catch (MethodNotFoundException $exception) {}

        $form->wellknown = 'ok';
        $this->assertEquals('ok', $form->wellknown);
        $this->assertNull($form->unknown);

        try {
            $form->type = 'error_type';
            $this->fail('Expected FatalThrowableError');
        } catch (FatalThrowableError $error) {}
    }

    private function getForm()
    {
        return new Form($this->getFormBuilder());
    }

    private function getFormBuilder()
    {
        /** @var FormBuilder|PHPUnit_Framework_MockObject_MockObject $formBuilder */
        $formBuilder = $this->getMockBuilder(FormBuilder::class)->disableOriginalConstructor()->getMock();

        return $formBuilder;
    }

    private function view($view = null, $data = [], $mergeData = [])
    {
        $factory = $this->createMock(Factory::class);
//            app(Factory::class);

        if (func_num_args() === 0) {
            return $factory;
        }

        return $factory->make($view, $data, $mergeData);
    }
}