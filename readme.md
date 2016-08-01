# Visual Paginator pro Nette Framework

```php
function renderDefault() {
    $model = $this->model->findAll();
    $this->setPaginator('paginator', $model);
    $this->template->model = $model;
}

function createComponentPaginator() {
    $paginator = $this->createPaginator(10); // 10 polozek na strance, klasicke odkazy

    $paginator =  $this->createPaginator(20, 'data'); // 20 polozek na strance, ajax -> invalidace snippetu 'data'

    $paginator->prev = 'Předchozí';
    $paginator->next = 'Další';
    $paginator->other = '...';

    return $paginator;
}
```