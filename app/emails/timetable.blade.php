<x-mail::message>
    @foreach ($data as $day)
        <p>{$day[0]['date']->getTranslateDayName('l')}
            
        </p>
    @foreach
</x-mail::message>