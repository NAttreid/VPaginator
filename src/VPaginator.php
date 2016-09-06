<?php

namespace NAttreid\VPaginator;

use Nette\Application\UI\Control;
use Nette\Utils\Paginator;

/**
 * Vpaginator for Nette
 *
 * @author David Grudl
 * @author Dusan Hudak
 * @author Attreid <attreid@gmail.com>
 */
class VPaginator extends Control
{

	/** @persistent */
	public $page = 1;

	/** @var array */
	public $onShowPage;

	/** @var int */
	public $pageAround = 3;

	/** @var int */
	public $othersPage = 2;

	/**
	 * Text na tlacitku predchozi
	 * @var string
	 */
	public $prev = '«';

	/**
	 * Text na tlacitku dalsi
	 * @var string
	 */
	public $next = '»';

	/**
	 * Text na tlacitku ostatni
	 * @var string
	 */
	public $other = '...';

	/** @var string */
	private $templateFile;

	/** @var Paginator */
	private $paginator;

	/** @var bool */
	private $isAjax = FALSE;

	/** @var bool */
	private $noHistory = FALSE;

	public function __construct()
	{
		parent::__construct();

		$reflection = $this->getReflection();
		$dir = dirname($reflection->getFileName());
		$this->templateFile = $dir . DIRECTORY_SEPARATOR . 'paginator.latte';
	}

	/**
	 * @param bool $value
	 * @return static
	 */
	public function setAjaxRequest($value = TRUE)
	{
		$this->isAjax = $value;
		return $this;
	}

	/**
	 * @param bool $value
	 * @return static
	 */
	public function setNoAjaxHistory($value = TRUE)
	{
		$this->noHistory = $value;
		return $this;
	}

	/**
	 * @return Paginator
	 */
	public function getPaginator()
	{
		if (!$this->paginator) {
			$this->paginator = new Paginator;
		}
		return $this->paginator;
	}

	/**
	 * @param int $page
	 */
	public function handleShowPage($page)
	{
		$this->onShowPage($this, $page);
	}

	/**
	 * @return string
	 */
	public function getTemplateFile()
	{
		return $this->templateFile;
	}

	/**
	 * @param string $file
	 * @return Vpaginator provides fluent interface
	 */
	public function setTemplateFile($file)
	{
		if ($file) {
			$this->templateFile = $file;
		}
		return $this;
	}

	/**
	 * Renders paginator.
	 * @return void
	 */
	public function render()
	{
		$paginator = $this->getPaginator();
		$page = $paginator->page;
		if ($paginator->pageCount < 2) {
			$steps = [$page];
			$viewed = FALSE;
		} else {
			$viewed = TRUE;

			$f = $first = $page - $this->pageAround;
			$l = $last = $page + $this->pageAround;
			if ($f < $paginator->firstPage) {
				$last += ($paginator->firstPage - $f);
			}
			if ($l > $paginator->lastPage) {
				$first -= ($l - $paginator->lastPage);
			}
			$arr = range(max($paginator->firstPage, $first), min($paginator->lastPage, $last));

			if ($this->othersPage > 0) {
				$count = $this->othersPage * 2;
				$quotient = ($paginator->pageCount - 1) / $count;
				for ($i = 0; $i <= $count; $i++) {
					$arr[] = round($quotient * $i) + $paginator->firstPage;
				}
				sort($arr);
				$steps = array_values(array_unique($arr));
			} else {
				$steps = $arr;
			}
		}

		$this->template->viewed = $viewed;
		$this->template->steps = $steps;
		$this->template->paginator = $paginator;
		$this->template->isAjax = $this->isAjax;
		$this->template->noHistory = $this->noHistory;
		$this->template->prev = $this->prev;
		$this->template->next = $this->next;
		$this->template->other = $this->other;
		if (count($this->onShowPage) > 0) {
			$this->template->handle = 'showPage!';
		} else {
			$this->template->handle = 'this';
		}

		$this->template->setFile($this->getTemplateFile());
		$this->template->render();
	}

	/**
	 * Loads state informations.
	 * @param  array
	 * @return void
	 */
	public function loadState(array $params)
	{
		parent::loadState($params);
		$this->getPaginator()->page = $this->page;
	}

}
