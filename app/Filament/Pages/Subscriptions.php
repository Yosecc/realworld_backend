<?php

namespace App\Filament\Pages;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Config;
use Filament\Forms\Get;
use Livewire\Component;
use Filament\Pages\Page;

use Filament\Infolists\Infolist;
use App\Models\SubscriptionProgram;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Infolists\Concerns\InteractsWithInfolists;

class Subscriptions extends Page implements HasForms, HasInfolists
{
    use InteractsWithInfolists;
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-star';

    protected static string $view = 'filament.pages.subscriptions';
    public User $user;

    public ?array $subscription;
    public $programs;
    public $html;

    public function mount(): void
    {
        $this->user = Auth::user();

        if($this->user->subscription && $this->user->subscription->package && $this->user->subscription->packagesPrice && $this->user->subscription->packagesPrice->is_recurrence){
            
            if($this->user->subscription->name == 'default' || $this->user->subscription->name == 'manual'){
              $price = $this->user->subscription->package->prices()->where('stripe_id',$this->user->subscription->stripe_price)->first();
            }
    
            $this->subscription = [
              'tittle' => $this->user->subscription->package->name,
              'type' => $this->user->subscription->name,
              'text' => $this->user->subscription->package->description,
              'recurrence' => isset($price) && $price->recurrence ? $price->recurrence->description : '',
              'mount' => isset($price) && $price->amount ?  $price->amount : '',
              'status' => $this->user->subscription->stripe_status,
              'package_id' => $this->user->subscription->package->id,
              'ends_at' => $this->user->subscription->ends_at ? Carbon::parse($this->user->subscription->ends_at)->format('m-d-Y') : ''
            ];
    
          }
    
          if($this->user->subscription && $this->user->subscription->package && $this->user->subscription->packagesPrice && !$this->user->subscription->packagesPrice->is_recurrence){
            
            $this->subscription = [
              'tittle' => $this->user->subscription->package->name,
              'type' => $this->user->subscription->name,
              'text' => $this->user->subscription->package->description,
              'recurrence' => $this->user->subscription->packagesPrice && $this->user->subscription->packagesPrice->recurrence ? $this->user->subscription->packagesPrice->recurrence->description : '',
              'mount' => $this->user->subscription->packagesPrice && $this->user->subscription->packagesPrice->amount ?  $this->user->subscription->packagesPrice->amount : '',
              'status' => $this->user->subscription->stripe_status,
              'package_id' => $this->user->subscription->package->id,
              'ends_at' => $this->user->subscription->ends_at ? Carbon::parse($this->user->subscription->ends_at)->format('m-d-Y') : ''
            ];
          }
    
          
          if($this->user->subscription && $this->user->subscription->ends_at != null && Carbon::now() > Carbon::parse($this->user->subscription->ends_at) && $this->user->subscription->price->is_recurrence){
            $this->subscription['status'] = 'canceled';
          }
    
          $this->programs = SubscriptionProgram::
                        where('status_id',1)
                        ->where('user_id', Auth::user()->id)
                        ->orderBy('created_at','desc')
                        ->where('is_recurrente',0)
                        ->with(['program','subscription'])
                        ->get();
    
          $this->programs = collect($this->programs)->map(function($p){ 
                      $p = collect($p);
                      if($p['subscription_id'] && $p['subscription']){
                        $nameProgram = $p['subscription']['package']['name'];
                        $p['program'] = collect([$p['program']])->map(function($p) use ( $nameProgram ) { 
                          $p['name'] =  $nameProgram ;
                          return $p;
                        })->first();
                      }
                        return $p; 
                      })->pluck('program');
            
        $this->html = Config::where('key','text_suscripcion')->first() ? Config::where('key','text_suscripcion')->first()->value : '';

    }

    public function productInfolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->state($this->subscription)
            // ->record($this->product)
            ->schema([
                
                Section::make('')
                    ->schema([
                        TextEntry::make('tittle')->size(TextEntry\TextEntrySize::Large)->label(''),
                        TextEntry::make('text')->label(''),
                        TextEntry::make('recurrence')->visible(function($record){    
                            return $this->subscription['mount'] != '' ? true : false;
                        })->helperText('$'.$this->subscription['mount'])->label(''),
                        TextEntry::make('status')
                            ->size(TextEntry\TextEntrySize::Large)
                            ->visible(function($record){    
                                return $this->subscription['status'] == 'canceled' ? true : false;
                            })
                            ->formatStateUsing(function(string $state) {
                                return 'Subscription Canceled';
                            })
                            ->helperText('You will be able to access the programsuntil '.$this->subscription['ends_at'])
                            ->label(''),
                        TextEntry::make('status')
                            ->size(TextEntry\TextEntrySize::Large)
                            ->visible(function($record){    
                                return $this->subscription['status'] == 'incomplete' ? true : false;
                            })
                            ->formatStateUsing(function(string $state) {
                                return 'Subscription Incomplete';
                            })
                            ->label(''),
                        
                    ])
            ]);
    }
}
