<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Tables;
use App\Models\Package;
use App\Models\Program;
use Filament\Forms\Get;
use Filament\Infolists;
use App\Models\Exercise;
use Filament\Forms\Form;
use App\Models\ProgramDay;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use App\Models\ProgramDayRoutine;
use App\Models\SubscriptionProgram;
use Filament\Forms\Components\Grid;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use App\Models\SubscriptionProgramLog;
use Filament\Support\Enums\FontWeight;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Repeater;
use Filament\Support\Enums\Alignment;  
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;
use Filament\Infolists\Components\Split;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Section;
use App\Models\SubscriptionProgramLogDetail;
use Filament\Infolists\Components\Actions;  
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Infolists\Components\ImageEntry;
use App\Filament\Resources\ProgramResource\Pages;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\RepeatableEntry;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Actions as ActionsForm;
use Filament\Forms\Components\Section as SectionForm;
use Filament\Forms\Components\Actions\Action as ActionForm;
use App\Filament\Resources\ProgramResource\RelationManagers;

class ProgramResource extends Resource
{
    protected static ?string $model = Program::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static bool $shouldRegisterNavigation = false;

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Split::make([
                    Section::make([
                        ViewEntry::make('video')->view('filament.infolists.entries.video')->state(function (Program $record) {
                            return Storage::disk('remoto')->url($record->video);
                        })
                    ])->grow(false),
                    Section::make([
                        ImageEntry::make('image')
                        ->state(function (Program $record) {
                            return Storage::disk('remoto')->url($record->image);
                        })
                        ->label('')
                        ->width('100%'),
                        // Infolists\Components\TextEntry::make('name')->weight(FontWeight::Bold)->label(''),
                        Infolists\Components\TextEntry::make('description')->label('')
                            ->markdown()
                            ->prose(),
                        Infolists\Components\TextEntry::make('program_category.description')->label(''),
                        Actions::make([
                            Action::make('start')
                            ->label('Start')
                            ->icon('heroicon-m-play')
                            ->color('success')
                            ->action(function (Program $record): void {

                                // dd($record);
                                $response = self::registerProgram([
                                    'subscription' => Auth::user()->subscription,
                                    'program' => $record,
                                    'status_id' => 1,
                                    'is_active' => 1
                                ]);
                                if(!$response['status']){
                                    Notification::make()
                                        ->title($response['message'])
                                        ->danger()
                                        ->send();
                                    return;
                                }
                               Notification::make()
                                    ->title($response['message'])
                                    ->success()
                                    ->send();
                            })
                            ->visible(function (Program $record){
                                return !self::statusProgram(['program' => $record]);
                            }),

                            Action::make('stop')
                            ->label('Stop')
                            ->icon('heroicon-m-stop')
                            ->color('danger')
                            ->action(function (Program $record): void {
                                $response = self::cancelProgram([
                                    'subscription' => Auth::user()->subscription,
                                    'program' => $record,
                                    'status_id' => 1,
                                    'is_active' => 1
                                ]);
                                if(!$response['status']){
                                    Notification::make()
                                        ->title($response['message'])
                                        ->danger()
                                        ->send();
                                    return;
                                }
                                Notification::make()
                                    ->title($response['message'])
                                    ->success()
                                    ->send();
                            })->visible(function (Program $record){
                                return self::statusProgram(['program' => $record]);
                            }),
                        ])->alignment(Alignment::Center),
                    ]),
                ])->columnSpanFull(),
                Section::make([
                    RepeatableEntry::make('details')->label('')
                        ->schema([
                            TextEntry::make('number')->weight(FontWeight::Bold)->label(''),
                            TextEntry::make('name')->label(''),
                            TextEntry::make('description')->label(''),
                            Actions::make([
                                Action::make('view')
                                ->label('Exercises')
                                ->icon('heroicon-m-eye')
                                ->fillForm(function (ProgramDay $record){
                                    $data['exercises'] = $record->exercise->map(function(ProgramDayRoutine $excercise){
                                        $excercise['ps'] = $excercise->programSuscription->id;
                                        $excercise['logs'] = $excercise->programSuscription->logs->where('program_day_routines_id',$excercise->id)->map(function($detail){
                                            $detail_id = $detail->id;
                                            return $detail->log_deatils->map(function($log) use($detail_id){
                                                $log['detail_id'] = $detail_id;
                                                return $log;
                                            });
                                        })->collapse();
                                        return $excercise;
                                    });
                                    return $data;
                                })
                                ->form([
                                    Repeater::make('exercises') #ProgramDayRoutine
                                    ->label('')
                                    ->schema([
                                        Grid::make(2)
                                        ->schema([
                                            ViewField::make('video')->view('filament.forms.components.video')->columnSpan('full'),
                                            TextInput::make('sets')->readOnly(),
                                            TextInput::make('repetitions')->readOnly(),
                                            Hidden::make('id'),
                                            Hidden::make('program_day_id'),
                                            Hidden::make('ps'),
                                            SectionForm::make('Log')
                                                ->schema([
                                                    Repeater::make('logs')
                                                        ->label('')
                                                        ->schema([
                                                            Hidden::make('set')->default(function (Get $get) {
                                                                return count($get('../../logs'));
                                                            })->label('Set')->required(),
                                                            TextInput::make('repeticiones')->label('Number of repetitions')->required(),
                                                            TextInput::make('peso')->label('Weight')->required(),
                                                        ])
                                                        ->itemLabel(fn (array $state): ?string => 'Set: '.$state['set'] ?? null)
                                                        ->addAction(
                                                            fn (ActionForm $action) => $action->label('Add log'),
                                                        )
                                                        ->reorderable(false)
                                                        ->columns(2),
                                                        ActionsForm::make([
                                                            ActionForm::make('save')
                                                            ->label('Save')
                                                            ->color('success')
                                                            ->action(function (array $data,Get $get): void {
                                                                self::processLog([
                                                                    'logs' => $get('logs'),
                                                                    'program_day_routines_id' => $get('id'),
                                                                    'program_days_id' => $get('program_day_id'),
                                                                    'subscription_programs_id' => $get('ps')
                                                                ]);
                                                                Notification::make()
                                                                    ->title('Saved successfully')
                                                                    ->success()
                                                                    ->send();
                                                            }),
                                                        ])->alignment(Alignment::Right)->columnSpan('full'),
                                                ])->collapsed()->columns(1)->columnSpan('full'),
                                            
                                        ])
                                    ])
                                    ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                                    ->addable(false)
                                    ->deletable(false)
                                    ->reorderable(false)
                                    ->collapsed()
                                    ->columns(1)                                    
                                ])
                                ->action(function (array $data): void {
                                    
                                })
                                // ->slideOver()
                            ])->alignment(Alignment::Right),
                        ])
                        ->columns(4)

                ])->visible(function (Program $record){
                    return self::statusProgram(['program' => $record]);
                })->columnSpanFull(),
            ]);
    }

    public static function statusProgram($request)
    {
        $program = $request['program'];
        $user = Auth::user();
    
        $status_package = null;
        $status_program = null;
          
        $v = self::calculaEstadoSuscripcion($user->subscription);
          
        if($user->subscription && $v && $user->subscription->package){
        
            $status_package = [ // saber si pago si no tiene sub llega null 
                "id"      => $user->subscription->package->id,
                "name"    => $user->subscription->package->name,
                "status"  => $v,
                "message" => "", //alert
                "subscription_id" => $user->subscription->id
            ];
        
            $subscriptionProgram = SubscriptionProgram::where('program_id',$program->id)
                                    ->where('subscription_id',$user->subscription->id)
                                    ->where('user_id',$user->id)
                                    ->latest()
                                    ->first();

            if($subscriptionProgram && $subscriptionProgram->status_id == 1){
                $status_program = [ //si el programa en el que enactivedd(tro esta registrado o no o nulo
                    "id"     => $subscriptionProgram->id,
                    "status" => $subscriptionProgram->is_active  ? true: false,
                    "active" => $subscriptionProgram->is_active,
                ];
            }

        }
    
        $subscriptionProgramNoRecurrente = SubscriptionProgram::where('program_id',$program->id)
                                ->where('user_id',$user->id)
                                ->where('is_recurrente',0)
                                ->latest()
                                ->first();
    
        if($subscriptionProgramNoRecurrente){
            $subscriptionProgram = $subscriptionProgramNoRecurrente;
        }

        if(isset($subscriptionProgram) && $subscriptionProgram->status_id == 1){
   
            $status_program = [ //si el programa en el que entro esta registrado o no o nulo
              "id"     => $subscriptionProgram->id,
              "status" => $subscriptionProgram->is_active  ? true: false,
              "active" => $subscriptionProgram->is_active,
            ];
    
            $status_package = [ // saber si pago si no tiene sub llega null 
              "id"      =>  $user->subscription && $user->subscription->package ? $user->subscription->package->id : null,
              "name"    =>  $user->subscription && $user->subscription->package ?  $user->subscription->package->name : 'null',
              "status"  => $v,
              "message" => "", //alert
              "subscription_id" => $user->subscription ? $user->subscription->id : null
            ];
    
        }
    
        if(!$user->subscription && (isset($subscriptionProgram) && $subscriptionProgram->status_id == 1)){
            $status_package['status'] = true;
        }
    
        if($program->is_free ){
    
            $status_program = [ 
              "id"     => null,
              "status" => true,
              "active" => true,
            ];
            
            $status_package = [ // saber si pago si no tiene sub llega null 
              "id"      => null,
              "name"    => '',
              "status"  => true,
              "message" => "", //alert
              "subscription_id" => null
            ];
        }

        return ($status_package['status'] && $status_program['status']) ? true:false;
    }

    public static function registerProgram($request)
    {
        $subscription = $request['subscription'];
        $program = $request['program'];
        if($subscription){
        
            $v = self::calculaEstadoSuscripcion($subscription);

            if(isset($subscription->package) && $subscription->package->unlimited){
                $v = true;
            }

            if(!$v){
                return ['status'=> false, 'message'=> 'Subscription '.$subscription->stripe_status ];
            }

            $package = Package::where('id',$subscription->package_id)->first();

            $subscription_program = SubscriptionProgram::where('subscription_id',$subscription->id)
                ->where('status_id',1)
                ->where('user_id',Auth::user()->id)
                ->orderBy('created_at','desc')
                ->where('is_recurrente',1)
                ->get();
          
      
            $subscription_program_per_user = SubscriptionProgram::where('subscription_id',$subscription->id)
                ->where('status_id',1)
                ->where('user_id',Auth::user()->id)
                ->where('program_id', $program->id)
                ->where('is_active',1)
                ->where('is_recurrente',1)
                ->get();

            $subscription_program_no_recurrente = SubscriptionProgram::where('program_id', $program->id)
                ->where('status_id',1)
                ->where('user_id',Auth::user()->id)
                ->orderBy('created_at','desc')
                ->where('is_recurrente',0)
                ->latest()
                ->first();
            
            if(count($subscription_program_per_user) > 0){
                return ['status'=> false, 'message'=> 'The user already has this program associated' ];
            }else{

                $programsActivos = $subscription_program->where('is_active',1);

                if(isset($package) && count($programsActivos) >= $package->number_of_programs && !$subscription_program_no_recurrente){

                    return ['status'=> false, 'message'=> "The user has reached and/or exceeded the program limit according to its associated package:"];
          
                }else{
  
                    if(!$subscription_program_no_recurrente){

                      $programsInactivo = $subscription_program->where('is_active',0)->where( 'program_id', $program->id );
  
                      if(count($programsInactivo)){
                        
                        foreach ($programsInactivo as $key => $value) {
                          if($value->program_id == $program->id){
                            $value->is_active = 1;
                            $value->save();
                          }
                        }
  
                        return ['status'=> true,'message'=>"Program Start"];
  
                      }else{
                        $user_program = new SubscriptionProgram();
                        $user_program->subscription_id = $subscription->id;
                        $user_program->program_id = $program->id;
                        $user_program->status_id = $request['status_id'];
                        $user_program->user_id = Auth::user()->id;
                        $user_program->is_recurrente = $subscription->packagesPrice->recurrence ? true : false;
                        $user_program->is_active = $request['is_active'];
                        $user_program->save();
                      }
                    }else{
                      if($subscription_program_no_recurrente->is_active == 0){
                        $subscription_program_no_recurrente->is_active = 1;
                      }else{
                        $subscription_program_no_recurrente->is_active = 0;
                      }
                      $subscription_program_no_recurrente->save();
                    }
  
                    $number = $package ? $package->number_of_programs - count($programsActivos) : 1;
                    $number = $number - 1;
  
                    return ['status'=> true,'message'=>"Congratulations, you've unlocked one program. With your subscription you can unlock ".$number." more"];
                  }
            }
        }else{
            $subscription_program = SubscriptionProgram::where('program_id',$program->id)
                ->where('status_id',1)
                ->where('user_id',Auth::user()->id)
                ->orderBy('created_at','desc')
                ->where('is_recurrente',0)
                ->latest()
                ->first();

            if($subscription_program){
                $subscription_program->is_active = 1;
                $subscription_program->save();
                return ['status'=> true,'message'=>"Program Start","subscription_program_id"=> $subscription_program->id];
            }

            return ['status'=> false, 'message'=> 'No subscription'];
        }
    }

    public static function cancelProgram($request)
    {
        $program = $request['program'];
        $subscription = $request['subscription'];

        $subscription_program = SubscriptionProgram::where('program_id', $program->id)->where('user_id',Auth::user()->id)->where('subscription_id', $subscription->id)->first();

        if($subscription_program){
            $subscription_program->is_active = 0;
            $subscription_program->save();
            return ['status'=> true, 'message'=> 'Program stop'];
        }

        return ['status'=> false, 'message'=> 'Program not found'];
    }

    public static function calculaEstadoSuscripcion($subscription)
    {
      $v = true;

      if($subscription && $subscription->stripe_status == 'canceled' && (Carbon::now() > Carbon::parse($subscription->ends_at))){
        $v = true;
      }
      elseif($subscription && ($subscription->stripe_status == 'active' || $subscription->stripe_status == 'trialing') && ($subscription->ends_at == null ||  ($subscription->stripe_status == 'trialing' && (Carbon::now() <= Carbon::parse($subscription->trial_ends_at)) )) ){
        $v = true;
        // dd('1');
      }
      elseif($subscription && ($subscription->stripe_status == 'active' || $subscription->stripe_status == 'trialing') && (Carbon::now() > Carbon::parse($subscription->ends_at))){
        $v = false;
        // dd('32');
      }
      else{
        $v = false;
        // dd('3');
      }

      // dd($v,$subscription);
      if($subscription && $subscription->package && $subscription->package->unlimited ){
        $v = true;
      }

      return $v;
    }

    public static function processLog($request)
    {
        
        $logs = collect($request['logs']);

        $num = 1;
        $logs->each(function($log, $index) use ($num, $request){
            // dd($num++);
            // dd($log,$request);
            if(isset($log['id'])){
                SubscriptionProgramLogDetail::where('id', $log['id'])->update([
                    'repeticiones' => $log['repeticiones'],
                    'peso' => $log['peso'],
                ]);
            }else{

                $ProgramLog = SubscriptionProgramLog::where('program_days_id', $request['program_days_id'])
                            ->where('program_day_routines_id', $request['program_day_routines_id'])
                            ->where('subscription_programs_id', $request['subscription_programs_id'])
                            ->first(); 

                if(!$ProgramLog){
                    $ProgramLog = new SubscriptionProgramLog();
                    $ProgramLog->program_days_id          = $request['program_days_id']; //Dia
                    $ProgramLog->program_day_routines_id  = $request['program_day_routines_id']; //Ejercicio
                    $ProgramLog->subscription_programs_id = $request['subscription_programs_id']; //subscription
                    $ProgramLog->status                   = 1;
                    $ProgramLog->is_complete              = 0;
                    $ProgramLog->save();
                }
                
                SubscriptionProgramLogDetail::where('id', $ProgramLog['id'])->updateOrInsert(
                        [
                            "set" => $log['set'],
                            "subscription_program_logs_id" => $ProgramLog->id,
                        ],
                        [
                            'repeticiones' => $log['repeticiones'],
                            'peso' => $log['peso'],
                            "created_at" => Carbon::now(),
                            "updated_at" => Carbon::now()
                        ]
                );


                $num++;

                $ejercicio = ProgramDayRoutine::where('id',$request['program_day_routines_id'])->first();
                
                $logs = SubscriptionProgramLogDetail::where('subscription_program_logs_id', $ProgramLog->id)->where('set',$log['set'])->get();
                
                if(count($logs) == $ejercicio->sets){ // completo el ejercicio
                    $ProgramLog->is_complete = 1;
                    $ProgramLog->save();
                }elseif(count($logs) < $ejercicio->sets){ //sNo lo ha completado
                    $ProgramLog->is_complete = 0;
                    $ProgramLog->save();
                }

            }
        });

    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListPrograms::route('/'),
            'create' => Pages\CreateProgram::route('/create'),
            'view' => Pages\ViewProgram::route('/{record}'),
            'edit' => Pages\EditProgram::route('/{record}/edit'),
        ];
    }
}
