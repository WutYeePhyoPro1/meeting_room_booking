<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>meeting_room_booking_system</title>
</head>
<body>
    <div class="mt-3 ps-3">
        <table class="table-responsive mt-4" style="width: 99%">
            <thead class="h-12 bg-slate-300 z-0">
                <tr>
                    <th class="text-left ps-2 rounded-tl-lg w-12">No</th>
                    <th class="text-left w-20">Room</th>
                    <th class="text-left">Date</th>
                    <th class="text-left">Meeeting Title</th>
                    <th class="text-left">Remark</th>
                    <th class="text-left">Start Time</th>
                    <th class="text-left">End Time</th>
                    <th class="text-left">Duration</th>
                    <th class="text-left">Meeting By</th>
                    <th class="text-left">Status</th>
                    <th class="text-left">Reason</th>
                    <th class="text-left rounded-tr-lg">Extended Time</th>
                </tr>
            </thead>
            <tbody class="font-light booking_body">
                @php
                    $i = 1;
                @endphp
                @foreach ($bookings as $item)
                    <tr class="h-10 hover:bg-slate-100">
                        <td class="ps-2">{{ $i }}</td>
                        <td >{{ $item->room->room_name }}</td>
                        <td class="">{{ $item->date }}</td>
                        <td class="">{{ $item->title }}</td>
                        <td class="">{{ $item->remark }}</td>
                        <td>{{ $item->start_time }}</td>
                        <td>{{ $item->end_time }}</td>
                        <td>{{ $item->duration }}</td>
                        <td>{{ $item->user->name . ($item->reception == 1 ? '(Reception)' : '') }}</td>
                        <td>
                            @switch($item->status)
                            @case(0)
                                <span>Pending</span>
                                @break
                            @case(1)
                                <span>Started</span>
                                @break
                            @case(2)
                                <span>Ended</span>
                                @break
                            @case(3)
                                <span>Cancelled</span>
                                @break
                            @case(4)
                                <span>Missed</span>
                                @break
                            @case(5)
                                <span>Finished</span>
                                @break
                                @default
                            @endswitch
                        </td>
                        <td>{{ $item->reason->reason }}</td>
                        <td>
                            @if ($item->extend_status)
                                {{ $item->extended_duration }}
                            @endif
                        </td>
                    </tr>
                    {{  $i++ }}
                @endforeach
            </tbody>
        </table>
        @if (isset($filters))
        <table class="mt-3">
            <tr>
                @if (isset($filters['room']))
                    <td><span class="mx-3">room:</span></td>
                    <td>{{ $filters['room'] }}</td>
                @endif

                @if (isset($filters['status']))
                    <td><span class="mx-3">status:</span></td>
                    <td>{{ get_status($filters['status']) }}</td>
                @endif

                @if (isset($filters['from_date']) && isset($filters['to_date']))
                    <td><span class="mx-3">{{ $filters['from_date'] .' ~ '.$filters['to_date'] }}</span></td>
                @endif

                @if (isset($filters['from_date']) && !isset($filters['to_date']))
                    <td><span class="mx-3">{{ $filters['from_date'] .' > ' }}</span></td>
                @endif

                @if (isset($filters['to_date']) && !isset($filters['from_date']))
                    <td><span class="mx-3">{{ '< '.$filters['to_date'] }}</span></td>
                @endif
            </tr>
        </table>
    @endif

</body>
</html>

