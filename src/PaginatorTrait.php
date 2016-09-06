<?php

namespace NAttreid\VPaginator;

use Nette\Database\Table\Selection;
use Nextras\Orm\Collection\ICollection;

trait PaginatorTrait
{

	/**
	 * Vytvori instanci paginatoru
	 * @param int $itemsPerPage
	 * @return VPaginator
	 */
	public function createPaginator($itemsPerPage = 10)
	{
		$control = new VPaginator();
		$paginator = $control->getPaginator();
		$paginator->itemsPerPage = $itemsPerPage;
		return $control;
	}

	/**
	 * Nastavi strankovani
	 * @param string $componentName
	 * @param $model
	 */
	public function setPaginator($componentName, $model)
	{
		/* @var $control VPaginator */
		$control = $this[$componentName];
		$paginator = $control->getPaginator();
		if ($model instanceof Selection) {
			$paginator->itemCount = $model->count();
			$model->limit($paginator->itemsPerPage, $paginator->offset);
		} elseif ($model instanceof ICollection) {
			$paginator->itemCount = $model->countStored();
			$model->limitBy($paginator->itemsPerPage, $paginator->offset);
		}
	}

	/**
	 * Vrati aktualni stranku
	 * @param string $componentName
	 * @return int
	 */
	public function getPage($componentName)
	{
		/* @var $control VPaginator */
		$control = $this[$componentName];
		$paginator = $control->getPaginator();
		return $paginator->getPage();
	}

}
