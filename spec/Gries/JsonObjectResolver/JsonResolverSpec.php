<?php

namespace spec\Gries\JsonObjectResolver;

use Gries\JsonObjectResolver\Stub\JsonResolvableCollectionStub;
use Gries\JsonObjectResolver\Stub\JsonResolvableStub;
use Gries\JsonObjectResolver\Stub\JsonResolvableWithRelationStub;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class JsonResolverSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Gries\JsonObjectResolver\JsonResolver');
    }

    function it_throws_an_exception_if_json_is_not_valid_json()
    {
        $this->shouldThrow('\InvalidArgumentException')->during('decode', ['not json']);
    }

    function it_resolves_to_stdClass_if_no_resolve_class_is_present()
    {
        $json = json_encode(['id' => 1]);

        $this->decode($json)->shouldBeInstanceOf('\stdClass');
        $this->decode($json)->shouldHaveProperty('id', 1);
    }

    function it_resolves_a_single_object()
    {
        $json = json_encode(['id' => 1, 'privateId' => 2, 'json_resolve_class' => 'Gries\JsonObjectResolver\Stub\JsonResolvableStub']);

        $result = $this->decode($json);
        $result->shouldBeInstanceOf('Gries\JsonObjectResolver\Stub\JsonResolvableStub');
        $result->shouldHaveProperty('id', 1);
        $result->getPrivateId()->shouldBe(2);
        $result->shouldNotHaveProperty('json_resolve_class', 'Gries\JsonObjectResolver\Stub\JsonResolvableStub');
    }

    function it_resolves_related_objects()
    {
        $json = json_encode(
            [
                'id' => 1,
                'relation' => [
                    'id' => 2,
                    'json_resolve_class' => 'Gries\JsonObjectResolver\Stub\JsonResolvableStub',
                ],
                'json_resolve_class' => 'Gries\JsonObjectResolver\Stub\JsonResolvableWithRelationStub',
            ]
        );

        $result = $this->decode($json);
        $result->shouldBeInstanceOf('Gries\JsonObjectResolver\Stub\JsonResolvableWithRelationStub');
        $result->shouldHaveProperty('id', 1);
        $result->relation->shouldBeInstanceOf('Gries\JsonObjectResolver\Stub\JsonResolvableStub');
        $result->relation->shouldHaveProperty('id', 2);
    }

    function it_sets_the_json_decode_class_property_on_encode_of_json_resolvable()
    {
        $object = new JsonResolvableStub();
        $object->id = 1;
        $expectedArrayData = json_encode(
            ['id' => 1, 'json_resolve_class' => 'Gries\JsonObjectResolver\Stub\JsonResolvableStub']
        );

        $this->encode($object)->shouldBeLike($expectedArrayData);
    }

    function it_encodes_related_objects()
    {
        $relation = new JsonResolvableStub();
        $relation->id = 2;

        $objectWithRelation = new JsonResolvableWithRelationStub();
        $objectWithRelation->id = 1;
        $objectWithRelation->relation = $relation;

        $expectedArrayData = json_encode(
            [
                'id' => 1,
                'relation' => [
                    'id' => 2,
                    'json_resolve_class' => 'Gries\JsonObjectResolver\Stub\JsonResolvableStub',
                ],
                'json_resolve_class' => 'Gries\JsonObjectResolver\Stub\JsonResolvableWithRelationStub',
            ]
        );

        $this->encode($objectWithRelation)->shouldBeLike($expectedArrayData);
    }

    function it_encodes_collection_of_related_objects()
    {
        $relation = new JsonResolvableStub();
        $relation->id = 2;

        $objectWithCollection = new JsonResolvableCollectionStub();
        $objectWithCollection->id = 1;
        $objectWithCollection->children = [$relation];

        $expectedArrayData = json_encode(
            [
                'id' => 1,
                'children' => [
                    0 => [
                        'id' => 2,
                        'json_resolve_class' => 'Gries\JsonObjectResolver\Stub\JsonResolvableStub',
                    ]
                ],
                'json_resolve_class' => 'Gries\JsonObjectResolver\Stub\JsonResolvableCollectionStub',
            ]
        );

        $this->encode($objectWithCollection)->shouldBeLike($expectedArrayData);
    }

    function it_decodes_collection_of_related_objects()
    {
        $json = json_encode(
            [
                'id' => 1,
                'children' => [
                    0 => [
                        'id' => 2,
                        'json_resolve_class' => 'Gries\JsonObjectResolver\Stub\JsonResolvableStub',
                    ]
                ],
                'json_resolve_class' => 'Gries\JsonObjectResolver\Stub\JsonResolvableCollectionStub',
            ]
        );

        $result = $this->decode($json);
        $result->shouldBeInstanceOf('Gries\JsonObjectResolver\Stub\JsonResolvableCollectionStub');
        $result->shouldHaveProperty('id', 1);
        $result->children[0]->shouldBeInstanceOf('Gries\JsonObjectResolver\Stub\JsonResolvableStub');
        $result->children[0]->shouldHaveProperty('id', 2);
    }

    public function getMatchers()
    {
        return [
            'beInstanceOf' => function($subject, $class) {
                    return ($subject instanceof $class);
                },
            'haveProperty' => function($subject, $property, $value) {
                    return (property_exists($subject, $property) && $subject->$property === $value);
                }
        ];
    }
}
