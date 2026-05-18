<?php

declare(strict_types=1);

namespace Tests\Unit\Providers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Actions\Action as TableAction;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\CreateAction as TableCreateAction;
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

    public function test_page_create_action_keeps_default_button_with_visible_label(): void
    {
        $action = CreateAction::make();

        $this->assertFalse($action->isIconButton());
        $this->assertTrue($action->isButton());
        $this->assertNull($action->getTooltip());
    }

    public function test_table_create_action_is_not_configured_as_icon_button(): void
    {
        $action = TableCreateAction::make();

        $this->assertFalse($action->isIconButton());
        $this->assertNull($action->getTooltip());
    }

    public function test_page_delete_action_keeps_default_button_with_visible_label(): void
    {
        $action = DeleteAction::make();

        $this->assertFalse($action->isIconButton());
        $this->assertTrue($action->isButton());
        $this->assertNull($action->getTooltip());
    }

    public function test_bulk_action_base_class_is_configured(): void
    {
        $action = BulkAction::make('archive')
            ->label('Archivar');

        $this->assertTrue($action->isIconButton());
        $this->assertSame('Archivar', $action->getTooltip());
    }
}
