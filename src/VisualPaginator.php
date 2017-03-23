<?php

namespace NAttreid\VisualPaginator;

use Nette\Application\UI\Control;
use Nette\Database\Table\Selection;
use Nette\Utils\Paginator;
use Nextras\Orm\Collection\ICollection;

/**
 * Vpaginator for Nette
 *
 * @property-read Paginator $paginator
 * @property-read int $itemCount
 * @property-read int $lastPage
 *
 * @author David Grudl
 * @author Dusan Hudak
 * @author Attreid <attreid@gmail.com>
 */
class VisualPaginator extends Control
{
	/** @int @persistent */
	public $page = 1;

	/** @var callable[] */
	public $onClick = [];

	/** @var int */
	public $pageAround = 3;

	/** @var int */
	public $othersPage = 2;

	/** @var string */
	public $prev = '«';

	/** @var string */
	public $next = '»';

	/** @var string */
	public $other = '...';

	/** @var string */
	private $templateFile;

	/** @var Paginator */
	private $paginator;

	/** @var bool */
	private $isAjax = false;

	/** @var bool */
	private $noHistory = false;

	public function __construct(int $itemsPerPage = 10)
	{
		parent::__construct();

		$this->paginator = new Paginator;
		$this->paginator->itemsPerPage = $itemsPerPage;

		$this->templateFile = __DIR__ . DIRECTORY_SEPARATOR . 'paginator.latte';
	}

	/**
	 * @param bool $value
	 * @return VisualPaginator
	 */
	public function setAjaxRequest(bool $value = true): self
	{
		$this->isAjax = $value;
		return $this;
	}

	/**
	 * @param bool $value
	 * @return VisualPaginator
	 */
	public function setNoAjaxHistory(bool $value = true): self
	{
		$this->noHistory = $value;
		return $this;
	}

	/**
	 * @param Selection|ICollection $model
	 */
	public function setPagination(&$model)
	{
		if ($model instanceof Selection) {
			$this->paginator->itemCount = $model->count();
			$model->limit($this->paginator->itemsPerPage, $this->paginator->offset);
		} elseif ($model instanceof ICollection) {
			$this->paginator->itemCount = $model->count();
			$model = $model->limitBy($this->paginator->itemsPerPage, $this->paginator->offset);
		}
	}

	/**
	 * @return Paginator
	 */
	protected function getPaginator(): Paginator
	{
		return $this->paginator;
	}

	/**
	 * @return int
	 */
	protected function getItemCount(): int
	{
		return $this->paginator->itemCount;
	}

	/**
	 * @return int
	 */
	protected function getLastPage(): int
	{
		return $this->paginator->lastPage;
	}

	/**
	 * @param int $page
	 */
	public function handleClick(int $page)
	{
		$this->onClick($this, $page);
	}

	/**
	 * @return string
	 */
	public function getTemplateFile(): string
	{
		return $this->templateFile;
	}

	/**
	 * @param string $file
	 * @return VisualPaginator
	 */
	public function setTemplateFile(string $file): self
	{
		if ($file) {
			$this->templateFile = $file;
		}
		return $this;
	}

	/**
	 * Renders paginator.
	 */
	public function render()
	{
		$page = $this->paginator->page;
		if ($this->paginator->pageCount < 2) {
			$steps = [$page];
			$viewed = false;
		} else {
			$viewed = true;

			$f = $first = $page - $this->pageAround;
			$l = $last = $page + $this->pageAround;
			if ($f < $this->paginator->firstPage) {
				$last += ($this->paginator->firstPage - $f);
			}
			if ($l > $this->paginator->lastPage) {
				$first -= ($l - $this->paginator->lastPage);
			}
			$arr = range(max($this->paginator->firstPage, $first), min($this->paginator->lastPage, $last));

			if ($this->othersPage > 0) {
				$count = $this->othersPage * 2;
				$quotient = ($this->paginator->pageCount - 1) / $count;
				for ($i = 0; $i <= $count; $i++) {
					$arr[] = round($quotient * $i) + $this->paginator->firstPage;
				}
				sort($arr);
				$steps = array_values(array_unique($arr));
			} else {
				$steps = $arr;
			}
		}

		$this->template->viewed = $viewed;
		$this->template->steps = $steps;
		$this->template->paginator = $this->paginator;
		$this->template->isAjax = $this->isAjax;
		$this->template->noHistory = $this->noHistory;
		$this->template->prev = $this->prev;
		$this->template->next = $this->next;
		$this->template->other = $this->other;
		if (count($this->onClick) > 0) {
			$this->template->handle = 'click!';
		} else {
			$this->template->handle = 'this';
		}

		$this->template->setFile($this->getTemplateFile());
		$this->template->render();
	}

	/**
	 * Loads state informations.
	 * @param array $params
	 */
	public function loadState(array $params)
	{
		parent::loadState($params);
		$this->paginator->page = $this->page;
	}

}
