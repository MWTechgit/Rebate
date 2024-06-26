@if (count(Nova::availableResources(request())))
    <h3 class="flex items-center font-normal text-white mb-6 text-base no-underline">
        <svg class="sidebar-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
            <path fill="var(--sidebar-icon)" d="M3 1h4c1.1045695 0 2 .8954305 2 2v4c0 1.1045695-.8954305 2-2 2H3c-1.1045695 0-2-.8954305-2-2V3c0-1.1045695.8954305-2 2-2zm0 2v4h4V3H3zm10-2h4c1.1045695 0 2 .8954305 2 2v4c0 1.1045695-.8954305 2-2 2h-4c-1.1045695 0-2-.8954305-2-2V3c0-1.1045695.8954305-2 2-2zm0 2v4h4V3h-4zM3 11h4c1.1045695 0 2 .8954305 2 2v4c0 1.1045695-.8954305 2-2 2H3c-1.1045695 0-2-.8954305-2-2v-4c0-1.1045695.8954305-2 2-2zm0 2v4h4v-4H3zm10-2h4c1.1045695 0 2 .8954305 2 2v4c0 1.1045695-.8954305 2-2 2h-4c-1.1045695 0-2-.8954305-2-2v-4c0-1.1045695.8954305-2 2-2zm0 2v4h4v-4h-4z"
            />
        </svg>
        <span class="sidebar-label">{{ __('Resources') }}</span>
    </h3>
    @foreach(Nova::groupedResources(request()) as $group => $resources)
        @if (count($resources) > 0)
            @if (count(Nova::groups(request())) > 1)
                <h4 class="ml-8 mb-4 text-xs text-white-50% uppercase tracking-wide">{{ $group }}</h4>
            @endif

            <ul class="list-reset mb-8">
                @foreach($resources as $resource)
                    @if (! $resource::$displayInNavigation)
                        @continue
                    @endif

                    @if (false == in_array($resource::label(), ['Applications', 'Claims']))
                        <li class="leading-tight mb-4 ml-8 text-sm">
                            <router-link :to="{
                                name: 'index',
                                params: {
                                    resourceName: '{{ $resource::uriKey() }}'
                                }
                            }" class="text-white text-justify no-underline dim">
                                {{ $resource::label() }}
                            </router-link>
                        </li>
                    @endif

                    @if ($resource::label() == 'Applications')
                        @php
                            $lenses = [
                                'application-inbox' => 'New & Pending',
                                'special-attention' => 'Special Attention',
                                'approved' => 'Approved',
                                'denied' => 'Denied',
                                'water-savers' => 'Water Savers'
                            ];
                        @endphp
                        @foreach ( $lenses as $lens => $label )
{{--                             <li class="leading-tight mb-4 ml-8 text-sm">
                                <a class="text-white text-justify no-underline dim" href="/admin/resources/applications/lens/{{ $lens }}">{{ $label }}</a>
                            </li> --}}
                            <li class="leading-tight mb-4 ml-8 text-sm">
                                <router-link :to="{
                                    name: 'lens',
                                    params: {
                                        resourceName: 'applications',
                                        lens: '{{ $lens }}'
                                    }
                                }" class="text-white text-justify no-underline dim">
                                    {{ $label }}
                                </router-link>
                            </li>
                        @endforeach
                        <li class="leading-tight mb-4 ml-8 text-sm">
                            <router-link :to="{
                                name: 'index',
                                params: {
                                    resourceName: '{{ $resource::uriKey() }}'
                                }
                            }" class="text-white text-justify no-underline dim">All Applications</router-link>
                        </li>
                    @endif

                    @if ($resource::label() == 'Claims')

                        @php
                            $lenses = [
                                'new-claims' => 'New & Pending',
                                'approved' => 'Approved',
                                'denied' => 'Denied',
                                'unclaimed' => 'Unclaimed',
                                'pending-export' => 'Pending Export',
                                'expiring-soon' => 'Expiring Soon',
                                'expired-recently' => 'Expired Recently',
                                'referrals' => 'Referrals',
                            ];
                        @endphp
                        @foreach ( $lenses as $lens => $label )
                            {{-- <li class="leading-tight mb-4 ml-8 text-sm">
                                <a class="text-white text-justify no-underline dim" href="/admin/resources/claims/lens/{{ $lens }}">{{ $label }}</a>
                            </li> --}}
                            <li class="leading-tight mb-4 ml-8 text-sm">
                                <router-link :to="{
                                    name: 'lens',
                                    params: {
                                        resourceName: 'claims',
                                        lens: '{{ $lens }}'
                                    }
                                }" class="text-white text-justify no-underline dim">
                                    {{ $label }}
                                </router-link>
                            </li>
                        @endforeach
                        <li class="leading-tight mb-4 ml-8 text-sm">
                            <router-link :to="{
                                name: 'index',
                                params: {
                                    resourceName: '{{ $resource::uriKey() }}'
                                }
                            }" class="text-white text-justify no-underline dim">All Claims</router-link>
                        </li>

                    @endif
                @endforeach
            </ul>
        @endif
    @endforeach
@endif

