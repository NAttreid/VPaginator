# Visual Paginator pro Nette Framework

```php
function renderDefault() {
    $model = $this->model->findAll();
    $this->setPaginator('paginator', $model);
    $this->template->model = $model;
}

function createComponentPaginator() {
    return $this->createPaginator(10); // 10 polozek na strance, klasicke odkazy
    return $this->createPaginator(20, 'data'); // 20 polozek na strance, ajax -> invalidace snippetu 'data'
}
```