<?php

declare(strict_types=1);

namespace App\Tests\Form;

use App\Entity\BlogCategory;
use App\Form\BlogCategoryType;
use Symfony\Component\Form\Test\TypeTestCase;

class BlogCategoryTypeTest extends TypeTestCase
{
    public function testSubmitValidData(): void
    {
        $now = new \DateTimeImmutable();

        $formData = [
            'id' => 1,
            'name' => 'Test',
            'active' => true,
            'sortOrder' => 1,
            'createdAt' => $now,
            'updatedAt' => $now,
        ];

        $form = $this->factory->create(BlogCategoryType::class);

        $object = new BlogCategory();
        $object->setId(1);
        $object->setName('Test');
        $object->setActive(true);
        $object->setSortOrder(1);
        $object->setCreatedAt($now);
        $object->setUpdatedAt($now);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($object->getId(), $form->get('id')->getData());
        $this->assertEquals($object->getName(), $form->get('name')->getData());
        $this->assertEquals($object->isActive(), $form->get('active')->getData());
        $this->assertEquals($object->getSortOrder(), $form->get('sortOrder')->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            if (!in_array($key, ['createdAt', 'updatedAt'])) {
                $this->assertArrayHasKey($key, $children);
            }
        }
    }
}
