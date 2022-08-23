<x-app-layout>
    <x-slot name="header">
        <div class="text-center content-header">
            <h2>Welcome to My Politics</h2>
            <h6>Engage. Debate. Make. Change</h6>
        </div>
    </x-slot>
    @if(!empty($politicians))
        <ul class="politicians-list-ul">
            @foreach($politicians as $item)

                <li>
                    <a target="" href="/get-politician/{{$item['id']}}">
                        {{-- <img src="{{asset($item['image'])}}" /> --}}
                        <img src="{{$item['image']}}" />
                    </a>
                    <span>{{$item['name_alias']}} {{$item['affiliation']}}</span>
                </li>
            @endforeach
        </ul>
        <x-nav-link :href="route('politicians.index')" class="btn btn-primary">
            View all
        </x-nav-link>
    @endif
</x-app-layout>
