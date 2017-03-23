# Visual Paginator pro Nette Framework

```php
class SomePresenter {

    function renderDefault() {
        $model = $this->model->findAll();
        $this['paginator']->setPagination($model);
        $this->template->model = $model;
    }

    function createComponentPaginator() {
        $paginator = new VPaginator(10); // 10 polozek na strance, klasicke odkazy

        $paginator = new VPaginator(20); // 20 polozek na strance, ajax -> invalidace snippetu 'data'

        $paginator->prev = 'Předchozí';
        $paginator->next = 'Další';
        $paginator->other = '...';
        
        $paginator->setAjaxRequest(); // volani pres ajax
        $paginator->setNoAjaxHistory(); // vypne historii pres ajax
        
        $paginator->onClick[] = function(VPaginator $paginator, $page){
            // php kod
        };

        return $paginator;
    }
}
```