<?php

namespace Morrislaptop\Firestore;

use Psr\Http\Message\UriInterface;
use Google\Cloud\Core\ArrayTrait;

/**
 * A Reference represents a specific location in your database and can be used
 * for reading or writing data to that database location.
 *
 * @see https://firebase.google.com/docs/reference/js/firebase.database.Reference
 */
class Query
{
    use ArrayTrait;

    /**
     * @var UriInterface
     */
    protected $uri;

    /**
     * @var ApiClient
     */
    protected $apiClient;

    /**
     * @var ValueMapper
     */
    protected $valueMapper;

    /**
     * @var array
     */
    protected $query = [];

    /**
     * Creates a new Reference instance for the given URI which is accessed by
     * the given API client and validated by the Validator (obviously).
     *
     * @param UriInterface $uri
     * @param ApiClient $apiClient
     * @param Validator|null $validator
     *
     * @throws InvalidArgumentException if the reference URI is invalid
     */
    public function __construct(UriInterface $uri, ApiClient $apiClient, ValueMapper $valueMapper = null, $query = [])
    {
        $this->uri = $uri;
        $this->apiClient = $apiClient;
        $this->valueMapper = $valueMapper ?? new ValueMapper(null, false);
        $this->query = $query;
    }

    public function documents(array $options = [])
    {
        $value = $this->apiClient->get($this->uri . '?' . http_build_query($options));

        if (empty($value)) {
            return [];
        }

        $rows = [];
        foreach ($value['documents'] as $doc)
        {
            $path = '/v1beta1/' . $doc['name'];
            $ref = new DocumentReference($this->uri->withPath($path), $this->apiClient);

            $data = $this->valueMapper->decodeValues($doc['fields']);

            $rows[] = new DocumentSnapshot($ref, [], $data, true);
        }

        return new QuerySnapshot($this, $rows);
    }

    /**
     * Add a WHERE clause to the Query.
     *
     * Example:
     * ```
     * $query = $query->where('firstName', '=', 'John');
     * ```
     *
     * ```
     * // Filtering against `null` and `NAN` is supported only with the equality operator.
     * $query = $query->where('coolnessPercentage', '=', NAN);
     * ```
     *
     * ```
     * // Use `array-contains` to select documents where the array contains given elements.
     * $query = $query->where('friends', 'array-contains', ['Steve', 'Sarah']);
     * ```
     *
     * @param string|FieldPath $fieldPath The field to filter by.
     * @param string $operator The operator to filter by.
     * @param mixed $value The value to compare to.
     * @return Query A new instance of Query with the given changes applied.
     * @throws \InvalidArgumentException If an invalid operator or value is encountered.
     */
    public function where($fieldPath, $operator, $value)
    {
        $filter = [
            'fieldFilter' => [
                'field' => [
                    'fieldPath' => $fieldPath,
                ],
                'op' => $operator,
                'value' => $this->valueMapper->encodeValue($value)
            ]
        ];

        $query = [
            'where' => [
                'compositeFilter' => [
                    'op' => Operator::PBAND,
                    'filters' => [
                        $filter
                    ]
                ]
            ]
        ];

        return $this->newQuery($query);
    }

    /**
     * Create a new Query instance
     *
     * @param array $additionalConfig
     * @param bool $overrideTopLevelKeys If true, top-level keys will be replaced
     *        rather than recursively merged.
     * @return Query A new instance of Query with the given changes applied.
     */
    private function newQuery(array $additionalConfig, $overrideTopLevelKeys = false)
    {
        $query = $this->query;

        if ($overrideTopLevelKeys) {
            $keys = array_keys($additionalConfig);
            foreach ($keys as $key) {
                unset($query[$key]);
            }
        }

        $query = $this->arrayMergeRecursive($query, $additionalConfig);

        return new self(
            $this->uri,
            $this->apiClient,
            $this->valueMapper,
            $query
        );
    }
}
