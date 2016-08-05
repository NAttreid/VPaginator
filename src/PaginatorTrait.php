<?php

namespace NAttreid\VPaginator;

trait PaginatorTrait {

    /**
     * Vytvori instanci paginatoru
     * @param int $itemsPerPage
     * @return VPaginator
     */
    public function createPaginator($itemsPerPage = 10) {
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
    public function setPaginator($componentName, $model) {
        $paginator = $this[$componentName]->getPaginator();
        if ($model instanceof \Nette\Database\Table\Selection) {
            $paginator->itemCount = $model->count();
            $model->limit($paginator->itemsPerPage, $paginator->offset);
        } elseif ($model instanceof \Nextras\Orm\Collection\ICollection) {
            $paginator->itemCount = $model->countStored();
            $model->limitBy($paginator->itemsPerPage, $paginator->offset);
        }
    }

    /**
     * Vrati aktualni stranku
     * @param string $componentName
     * @return int
     */
    public function getPage($componentName) {
        $paginator = $this[$componentName]->getPaginator();
        return $paginator->getPage();
    }

}
