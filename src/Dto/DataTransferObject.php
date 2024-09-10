<?php

namespace App\Dto;


abstract class DataTransferObject
{
    /**
     * @return array
     */
    public function toArray(): array
    {
        $vars = [];

        $this->convert($this, $vars);

        return $vars;
    }

    private function convert(DataTransferObject $dto, array &$result): void
    {
        $public_properties = static::getPublicProperties($dto);

        foreach (get_object_vars($dto) as $prop_name => $prop_value) {
            if (!in_array($prop_name, $public_properties)) {
                continue;
            }

            if (is_array($prop_value) && $prop_value &&
                is_subclass_of(reset($prop_value), DataTransferObject::class)) {
                if (!isset($result[$prop_name])) {
                    $result[$prop_name] = [];
                }

                /** @var DataTransferObject $prop_sub_value */
                foreach ($prop_value as $prop_sub_value) {
                    $sub_result = [];
                    $this->convert($prop_sub_value, $sub_result);

                    $result[$prop_name][] = $sub_result;
                }

                continue;
            }

            if (is_subclass_of($prop_value, DataTransferObject::class)) {
                $result[$prop_name] = $prop_value->toArray();
            } else {
                $result[$prop_name] = $prop_value;
            }
        }
    }

    private static function getPublicProperties(DataTransferObject $dto): array
    {
        return array_map(
            fn(\ReflectionProperty $property) => $property->getName(),
            (new \ReflectionClass($dto))->getProperties(\ReflectionProperty::IS_PUBLIC)
        );
    }

    public static function fromArray(array $data): static {
        $dto = new static();

        $public_properties = get_object_vars($dto);

        foreach ($public_properties as $prop_name => $prop_value) {
            if (array_key_exists($prop_name, $data)) {
                $dto->{$prop_name} = $prop_value;
            }
        }

        return $dto;
    }
}