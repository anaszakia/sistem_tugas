<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TugasResource\Pages;
use App\Filament\Resources\TugasResource\RelationManagers;
use App\Models\Tugas;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\FileUpload;

class TugasResource extends Resource
{
    protected static ?string $model = Tugas::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Tugas';
    protected static ?string $navigationGroup = 'Tugas Management';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('id_user')
                    ->label('Karyawan')
                    ->relationship('user', 'name') // Menggunakan nama relasi yang ada di model
                    ->searchable()
                    ->preload() // Opsional: untuk memuat data saat dropdown dibuka
                    ->required(),
                Forms\Components\TextInput::make('nama_tugas')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('deadline')
                    ->required(),
                Forms\Components\FileUpload::make('file')
                    ->required()
                    ->image()
                    ->imagePreviewHeight('250')
                    ->disk('public') // WAJIB: agar URL cocok
                    ->directory('/') // atau sesuai dengan struktur kamu
                    ->downloadable()
                    ->openable()
                    ->imageEditor(),
                Forms\Components\Select::make('status')
                     ->label('Status Tugas')
                    ->options([
                        'menunggu' => 'Menunggu',
                        'proses' => 'Dalam Proses',
                        'selesai' => 'Selesai',
                    ])
                    ->required()
                    ->native(false) // Untuk tampilan yang lebih modern
                    ->placeholder('Pilih Status')
                    ->default('menunggu')
                ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Nama')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nama_tugas')
                    ->searchable(),
                Tables\Columns\TextColumn::make('deadline')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('file')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTugas::route('/'),
            'create' => Pages\CreateTugas::route('/create'),
            'edit' => Pages\EditTugas::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->can('view_any_tugas');
    }

}
