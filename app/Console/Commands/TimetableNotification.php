<?php
 
namespace App\Console\Commands;
 
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use illuminate\Support\Facades\Mail;
use Carbon\Carbon;

 
 
class TimetableNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:timetable-notification';
 
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
 
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $response = Http::get('https://tahvel.edu.ee/hois_back/timetableevents/timetableByGroup/36', [
            'from' => now()->startOfWeek()->addWeek()->toIsoString(),
            'lang' => 'ET',
            'studentGroups' => 7596,
            'thru' => now()->addWeek()->endOfWeek()->toIsoString(),
        ]);
     
        $data = collect($response->json()['timetableEvents'])->map(function ($entry) {
            return [
                'name' => data_get($entry, 'nameEt', '-'),
                'room' => data_get($entry, 'rooms.0.roomCode', 'pole'),
                'teacher' => data_get($entry, 'teachers.0.name', 'pole'),
                'date' => Carbon::parse(data_get($entry, 'date')),
                'time_start' => data_get($entry, 'timeStart', ''),
                'time_end' => data_get($entry, 'timeEnd', ''),
            ];
        })->sortBy(['date', 'time_start'])
          ->groupBy(function ($event) {
              return $event['date']->format('m-d');
          });
     
          return $data;
        //collect(['kristofer.mere@ametikool.ee'])
          //  ->each(fn ($user) => Mail::to($user)->send(new Timetable($data)));
    }
}