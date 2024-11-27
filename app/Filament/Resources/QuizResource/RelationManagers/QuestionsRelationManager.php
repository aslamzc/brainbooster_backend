<?php

namespace App\Filament\Resources\QuizResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Columns\SelectColumn;

class QuestionsRelationManager extends RelationManager
{
    protected static string $relationship = 'question';

    protected static ?string $title = 'Questions';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('question')->label('Question')->searchable(),
                SelectColumn::make('status')
                    ->label('Status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                    ]),
            ])
            ->filters([])
            ->headerActions([])
            ->actions([
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}
