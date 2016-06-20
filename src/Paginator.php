<?php

namespace NAttreid\VPaginator;

trait Paginator {

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
     * @param \Nette\Database\Table\Selection $model
     */
    public function setPaginator($componentName, \Nette\Database\Table\Selection $model) {
        $paginator = $this[$componentName]->getPaginator();
        $paginator->itemCount = $model->count();
        $model->limit($paginator->itemsPerPage, $paginator->offset);
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
