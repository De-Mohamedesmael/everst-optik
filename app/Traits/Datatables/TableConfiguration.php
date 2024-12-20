<?php

namespace App\Traits\Datatables;

trait TableConfiguration
{
    public int $index = 0;

    public function bootWithSorting()
    {
        $this->defaultSortColumn = (new $this->model)->getTable().'.id';
        $this->defaultSortDirection = 'desc';
    }

    public function initializeTableConfiguration()
    {
        $this->listeners = array_merge($this->listeners, [
            'refreshDatatable' => 'resetIndex',
        ]);
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setBulkActionsEnabled();

        $this->setHideBulkActionsWhenEmptyEnabled();

        $this->setBulkActions([
            'deleteSelected' => 'Delete Selected',
        ]);

        $this->setTableWrapperAttributes([
            'class' => 'table align-middle table-row-dashed fs-6 gy-5',
        ]);

        $this->setTrAttributes(function ($row) {
            return [
                'class' => 'text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0',
            ];
        });

        $this->setThAttributes(function ($row) {
            return [
                'class' => 'ps-0',
            ];
        });

        $this->setTbodyAttributes([
            'class' => 'fw-bold text-gray-600',
        ]);
        $this->setFilterLayoutSlideDown();

    }

    public function deleteSelected()
    {
        $this->emit('deleteAll', $this->getSelected());
    }

    public function resetIndex()
    {
        $this->index = 0;
    }
}
