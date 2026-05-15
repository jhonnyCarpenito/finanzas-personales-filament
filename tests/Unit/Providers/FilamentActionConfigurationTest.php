<?php

declare(strict_types=1);

namespace Tests\Unit\Providers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Actions\Action as TableAction;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Tests\TestCase;

final class FilamentActionConfigurationTest extends TestCase
{
    public function test_table_row_actions_use_icon_button_with_label_tooltip(): void
    {
        $action = EditAction::make();

        $this->assertTrue($action->isIconButton());
        $this->assertSame(__('filament-actions::edit.single.label'), $action->getTooltip());
    }

    public function test_custom_table_actions_use_icon_button_with_custom_label_tooltip(): void
    {
        $action = TableAction::make('duplicate')
            ->label('Duplicar');

        $this->assertTrue($action->isIconButton());
        $this->assertSame('Duplicar', $action->getTooltip());
    }

    public function test_bulk_actions_use_icon_button_with_label_tooltip(): void
    {
        $action = DeleteBulkAction::make();

        $this->assertTrue($action->isIconButton());
        $this->assertSame(__('filament-actions::delete.multiple.label'), $action->getTooltip());
    }

    public function test_page_create_action_uses_icon_button_with_label_tooltip(): void
    {
        $action = CreateAction::make();

        $this->assertTrue($action->isIconButton());
        $this->assertIsString($action->getLabel());
        $this->assertSame($action->getLabel(), $action->getTooltip());
    }

    public function test_page_delete_action_uses_icon_button_with_label_tooltip(): void
    {
        $action = DeleteAction::make();

        $this->assertTrue($action->isIconButton());
        $this->assertSame(__('filament-actions::delete.single.label'), $action->getTooltip());
    }

    public function test_bulk_action_base_class_is_configured(): void
    {
        $action = BulkAction::make('archive')
            ->label('Archivar');

        $this->assertTrue($action->isIconButton());
        $this->assertSame('Archivar', $action->getTooltip());
    }
}
