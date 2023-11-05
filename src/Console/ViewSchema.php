<?php

namespace NiclasTimm\EloquentSchemaViewer\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ViewSchema extends Command
{
    protected $signature = 'eloquent-schema-viewer:view
                            {--T|table= : Name of the database table (required if model is not provided)}
                            {--M|model= : Name of the database table (required if table is not provided)}';

    protected $description = 'Shows the database schema of an eloquent model';

    protected function configure()
    {
        $this->setAliases([
            'esv:view',
            'esv:view-schema',
        ]);
        parent::configure();
    }

    public function handle(): void
    {
        if (!$this->option('table') && !$this->option('model')) {
            $this->error('Please provide a table or model name');

            exit(1);
        }

        $table = $this->getTable();

        if (!Schema::hasTable($table)) {
            $this->error('Table '.$table.' does not exist');

            exit(1);
        }

        $columns = $this->getColumns($table);

        $tableHead = $this->getTableHead();

        $rows = $this->getRows($columns);

        $this->table($tableHead, $rows);
    }

    private function getTable(): string
    {
        return $this->option('table')
            ? $this->option('table')
            : app('App\Models\\'.$this->option('model'))->getTable();
    }

    private function getColumns(string $table): array
    {
        return DB::getDoctrineSchemaManager()->listTableColumns($table);
    }

    private function getTableHead(): array
    {
        return ['Name', 'Type', 'Auto increment', 'Nullable', 'Default'];
    }

    private function getRows(array $columns): array
    {
        return array_map(function ($column) {
            return [
                $column->getName(),
                $column->getType()->getName(),
                $column->getAutoincrement() ? 'Yes' : '',
                !$column->getNotnull() ? 'Yes' : '',
                $column->getDefault(),
            ];
        }, array_values($columns));
    }
}
