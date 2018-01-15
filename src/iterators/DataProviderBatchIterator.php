<?php
namespace kak\widgets\grid\iterators;

use Iterator;
use Countable;
use kak\widgets\grid\mappers\ColumnMapper;
use yii\data\BaseDataProvider;
use yii\data\Pagination;
use yii\db\ActiveRecordInterface;

class DataProviderBatchIterator implements Iterator , Countable
{
    /*** @var \yii\data\BaseDataProvider */
    private $dataProvider;
    /*** @var int */
    private $totalItemCount = -1;
    /*** @var int */
    private $currentIndex = -1;
    /*** @var int */
    private $currentPage = 0;
    /*** @var array */
    private $items;
    /** @var ColumnMapper */
    private $mapper;

    /**
     * DataProviderBatchIterator constructor.
     * @param BaseDataProvider $dataProvider
     * @param ColumnMapper $mapper
     */
    public function __construct(BaseDataProvider $dataProvider, ColumnMapper $mapper)
    {
        $this->dataProvider = $dataProvider;
        $this->mapper = $mapper;
        $this->totalItemCount = $dataProvider->getTotalCount();

        if (($pagination = $this->dataProvider->getPagination()) === false) {
            $this->dataProvider->setPagination($pagination = new Pagination());
        }

    }

    /**
     * @return int
     */
    public function getCurrentIndex()
    {
        return $this->currentIndex;
    }

    /**
     * @return int
     */
    public function getCurrentPage()
    {
        return $this->currentPage;
    }

    /**
     * Return data provider of iterate
     * @return BaseDataProvider
     */
    public function getDataProvider()
    {
        return $this->dataProvider;
    }

    /**
     * Return count of iterate
     * @return int
     */
    public function getTotalItemCount()
    {
        return $this->totalItemCount;
    }


    public function loadData()
    {
        $this->getDataProvider()->getPagination()->setPage($this->getCurrentPage());
        $this->getDataProvider()->prepare(true);

        return $this->items = $this->getDataProvider()->getModels();
    }




    /**
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since 5.0.0
     */
    public function current()
    {
        return $this->getItem($this->getCurrentIndex());
    }

    /**
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next()
    {
        $pageSize = $this->getDataProvider()->getPagination()->pageSize;
        $this->currentIndex++;
        if ($this->currentIndex >= $pageSize) {
            $this->currentPage++;
            $this->currentIndex = 0;
            $this->loadData();
        }
    }

    /**
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key()
    {
        $pageSize = $this->getDataProvider()->getPagination()->pageSize;
        return $this->getCurrentPage() * $pageSize + $this->getCurrentIndex();
    }

    /**
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     * @since 5.0.0
     */
    public function valid()
    {
        return $this->key() < $this->getTotalItemCount();
    }




    /**
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind()
    {
        $this->currentIndex = 0;
        $this->currentPage = 0;
        $this->loadData();

    }

    /**
     * Count elements of an object
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     * @since 5.1.0
     */
    /**
     * Gets the total number of items in the dataProvider.
     * This method is required by the Countable interface.
     * @return integer the total number of items
     */
    public function count()
    {
        return $this->getTotalItemCount();
    }

    /**
     * @param $index
     * @return mixed
     */
    public function getItem($index)
    {
        return $this->mapper->map($this->items[$index], $index);
    }




}