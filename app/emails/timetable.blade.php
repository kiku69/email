<x-mail::message>
    @foreach ($data as $day)
        <p>{$day[0]['date']->getTranslateDayName}</p>
    @foreach
</x-mail::message>