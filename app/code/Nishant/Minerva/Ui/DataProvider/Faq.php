<?php declare (strict_types = 1);

namespace Nishant\Minerva\Ui\DataProvider;

use Magento\Ui\DataProvider\AbstractDataProvider;
use Nishant\Minerva\Model\ResourceModel\Faq\Collection;
use Nishant\Minerva\Model\ResourceModel\Faq\CollectionFactory;

class Faq extends AbstractDataProvider
{
    /** @var Collection $collection */
    protected $collection;

    /** @var array */
    private array $loadedData = [];

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        string $name,
        string $primaryFieldName,
        string $requestFieldName,
        CollectionFactory $collectionFactory,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        if (empty($this->loadedData)) {
            foreach ($this->collection->getItems() as $item) {
                $this->loadedData[$item->getId()] = $item->getData();
            }
        }
        return $this->loadedData;
    }
}
